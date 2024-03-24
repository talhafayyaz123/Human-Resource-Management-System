<?php

namespace App\Http\Requests;

use App\Traits\CustomHelper;
use Illuminate\Foundation\Http\FormRequest;

class MyFeedRequest extends FormRequest
{
    use CustomHelper;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'text' => 'required_if:isVote,0',
            'isVote' => 'required|boolean',
            'moduleId' => 'required',
            'modelName' => 'required',
            'mentionUserIds' => 'nullable|array',
            'tags' => 'nullable|array',
            'voteAnswers' => 'required_if:isVote,1|array',
            'hashTags' => 'nullable|array',
            'image' => 'nullable',
            'image.base64' => 'nullable',
            'image.name' => 'nullable',
            'image.size' => 'nullable',
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'userId' => $this->getAuthUserId($request),
            'text' => $request['text'],
            'isVote' => $request['isVote'],
            'pollFinished' => $request['pollFinished'],
            'pollQuestion' => $request['pollQuestion'],
            'pollDueDate' => $request['pollDueDate'],
            'currentRoutePath' => $request['currentRoutePath'],
            'moduleableType' => "App\Models\\" . $request['modelName'],
            'moduleableId' => $request['moduleId'],
        ];
    }
}
