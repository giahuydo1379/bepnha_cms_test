<?php namespace App\Http\Requests\Inside;

use App\Http\Requests\Request;

class DocumentsRequest extends Request
{
    private $_languages = array();
    public function __construct()
    {
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = array();
        $data['title']         = 'required';
        $data['content']               = 'required';
        $data['time_to_done']           = 'required';
        $data['category_id']           = 'required';
        $data['pcategory_id']           = 'required';
        $data['image_name']         = 'required';
        //$data['image_name1']         = 'required';

        return $data;
    }

    public function messages()
    {
        $data = array();
        $data["title.required"]    = 'Vui lòng nhập tên Sổ tay.';
        $data["content.required"]          = 'Vui lòng nhập công thức.';
        $data["time_to_done.required"]      = 'Vui lòng nhập thời gian hoàn thành.';
        $data["time_to_done.time_to_done"]      = 'Vui lòng nhập thời gian theo mẫu gợi ý';
        $data["pcategory_id.required"]      = 'Vui lòng chọn category chính';
        $data["category_id.required"]      = 'Vui lòng chọn category phụ';
        
        $data["image_name.required"]    = 'Vui lòng chọn Image Cover.';
        //$data["image_name1.required"]    = 'Vui lòng chọn Image Background.';
        return $data;
    }
}
