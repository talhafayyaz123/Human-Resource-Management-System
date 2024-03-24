<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExtractTextController extends Controller
{
   
    /**
     * Extract text inside $t() from a string
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function extract(Request $request)
    {
        function extractText($str)
        {
            $result = [];
            preg_match_all('/\$t\([\'"]([^\'"]+)[\'"]\)/', $str, $matches);
            foreach ($matches[1] as $match) {
                $result[$match] = "";
            }
            return $result;
        }

        $output = [];
        $files = $request->file('files');
        foreach ($files as $file) {
            $file_ext = strtolower($file->getClientOriginalExtension());
            if ($file_ext == "vue") {
                $file_contents = file_get_contents($file);
                $output[] = extractText($file_contents);
            }
        }
        $output = call_user_func_array('array_merge', $output); // merge arrays
        return response()->json($output);
    }
}