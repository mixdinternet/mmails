<?php
namespace Mixdinternet\Mmails\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEditMmailsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required'
            , 'name' => 'required|max:150'
            , 'toName' => 'required|max:150'
            , 'to' => 'required|email'
            , 'subject' => 'required|max:150'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

}