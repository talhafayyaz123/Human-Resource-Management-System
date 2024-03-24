<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductParameter;
use App\Models\ProductVersion;
use App\Models\System;
use App\Models\UploadedFile;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Net\SFTP;
use phpseclib3\Net\SSH2;

class InstallSystemService
{
    public function uploadFiles(SFTP $sftp, UploadedFile $file, string $system = 'windows'): array
    {
        try {
            $file_to_upload =  Storage::disk('local')->get('product/files/' . $file->storage_name);
            if ($system == 'windows') {
                $sftp->put('C:' . $file->storage_name, $file_to_upload);
                return ['message' => 'File ' . $file->storage_name . ' successfully uploaded', 'error' => false];
            } else if ($system == 'linux') {
                $sftp->put('/srv' . $file->storage_name, $file_to_upload);
                return ['message' => 'File ' . $file->storage_name . ' successfully uploaded', 'error' => false];
            } else {
            }
        } catch (Exception $e) {
            return ['message' => 'An exception has occured while uploading files' . $e->getMessage(), 'error' => true];
        }
    }

    public function executeProductParams(SSH2 $ssh, ProductParameter $param): array
    {
        try {
            $key = $param->key;
            $value = $param->value;
            $keyResult = $ssh->exec("export {$key}={$value}; echo \${$key}");
            return ['message' => "Key " . $key . " successfully executed. Result:" . $keyResult, "error" => false];
        } catch (Exception $e) {
            return ['message' => 'An exception has occured while executing params' . $e->getMessage(), 'error' => true];
        }
    }

    public function executeSystemCommands(SSH2 $ssh, ProductVersion $product_version, System $system): array
    {
        $result = '';
        try {
            if ($system->operating_system == 'linux') {
                if ($system->type === 'premise' && !empty($product_version->routine->premise_linux_install_routine)) {
                    $commands_to_execute = explode("\n", $product_version->routine->premise_linux_install_routine);
                    foreach ($commands_to_execute as $command) {
                        $result = $ssh->exec($command);
                    }
                    $getData =  array_filter(explode("\n", $result));
                    return ['message' => 'Command ' . $product_version->routine->premise_linux_install_routine . ' successfully executed. Result:' . json_encode($getData, JSON_UNESCAPED_SLASHES), 'error' => false];
                } else if ($system->type === 'private' && !empty($product_version->routine->private_linux_install_routine)) {
                    $commands_to_execute = explode("\n", $product_version->routine->private_linux_install_routine);
                    foreach ($commands_to_execute as $command) {
                        $result = $ssh->exec($command);
                    }
                    $getData =  array_filter(explode("\n", $result));
                    return ['message' => 'Command ' . $product_version->routine->private_linux_install_routine . ' successfully executed. Result:' . json_encode($getData, JSON_UNESCAPED_SLASHES), 'error' => false];
                } else if (!empty($product_version->routine->public_linux_install_routine)) {
                    $commands_to_execute = explode("\n", $product_version->routine->public_linux_install_routine);
                    foreach ($commands_to_execute as $command) {
                        $result = json_encode($ssh->exec($command));
                    }
                    $getData =  array_filter(explode("\n", $result));
                    return ['message' => 'Command ' . $product_version->routine->public_linux_install_routine . ' successfully executed. Result:' . json_encode($getData, JSON_UNESCAPED_SLASHES), 'error' => false];
                }
            }
            /* else if ($system->operating_system == 'mac') {
                if ($system->type === 'premise' && !empty($product->premise_mac_install_routine)) {
                    $commands_to_execute = explode("\n", $product->premise_mac_install_routine);
                    foreach ($commands_to_execute as $command) {
                        $ssh->exec($command);
                    }
                    return ['message' => 'Command ' . $product->premise_mac_install_routine . ' successfully executed', 'error' => false];
                } else if ($system->type === 'private' && !empty($product->private_mac_install_routine)) {
                    $commands_to_execute = explode("\n", $product->private_mac_install_routine);
                    foreach ($commands_to_execute as $command) {
                        $ssh->exec($command);
                    }
                    return ['message' => 'Command ' . $product->private_mac_install_routine . ' successfully executed', 'error' => false];
                } else if (!empty($product->public_mac_install_routine)) {
                    $commands_to_execute = explode("\n", $product->public_mac_install_routine);
                    foreach ($commands_to_execute as $command) {
                        $ssh->exec($command);
                    }
                    return ['message' => 'Command ' . $product->public_mac_install_routine . ' successfully executed', 'error' => false];
                }
            }*/
            return ['message' => 'No Command found to run', 'error' => false];
        } catch (Exception $e) {
            return ['message' => 'An exception has occured while executing commands' . $e->getMessage(), 'error' => true];
        }
    }


    public function executeCustomCommands(System $system, Request $request)
    {
        return response()->stream(function () use ($request, $system) {
            try {

                $host = $system->server_ip;
                $port = $system->server_port ?? 22;
                $username = $system->username;
                $password = $system->password;
                $ssh = new SSH2($host, $port);
                if (!$ssh->login($username, $password)) {
                    echo 'data: Not connected either username or password is incorrect';
                    /** use to stream the data */
                    ob_flush();
                    flush();
                    echo "\n\n";
                    /** */
                    return;
                }
                $commands_to_execute = json_decode($request->command);
                foreach ($commands_to_execute as $command) {
                    $result = $ssh->exec($command);
                    $getData = array_filter(explode("\n", $result));
                    echo 'data:Command successfully executed. Result:' . json_encode($getData);
                    ob_flush();
                    flush();
                    echo "\n\n";
                }
            } catch (Exception $e) {
                echo 'data: An exception has occured while executing ' . $e->getMessage();
                /**use to stream the data */
                ob_flush();
                flush();
                echo "\n\n";
                /** */
                return;
            }
            return;
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
    }
}
