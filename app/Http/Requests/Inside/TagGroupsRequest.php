<?php namespace App\Http\Requests\Inside;

use App\Http\Requests\Request;

class TagGroupsRequest extends Request {

private $_languages = array();
public function __construct() {

    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $data = array();
        $data['title']       = 'required';
        $data['tags']       = 'required';
        return $data;
    }

    public function messages() {
        $data = array();
        $data["title.required"] = 'Vui lòng nhập tên Tags.';
        $data["tags.required"] = 'Vui lòng nhập tên danh sách Tags.';

        return $data;
    }
}