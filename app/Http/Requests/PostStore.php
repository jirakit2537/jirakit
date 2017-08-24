<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStore extends FormRequest
{
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
        return [
          'title' => 'required',
          'slug' => 'required|unique:posts,slug' ,
          'summary' => 'required',
          'content' => 'required',
          'category_id' => 'required',
          'featured_image' => 'required|mimes:jpg,jpeg,png|max:3500',
        ];


    }
    public function messages()
{
return [
    'title.required' => 'กรุณากรอก ชื่อบล็อค',
    'slug.required'  => 'กรุณากรอก URL',
    'slug.unique'  => 'ชื่อ URL นี้มีอยู่แล้ว',
];
}
}
