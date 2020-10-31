<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddArtRequest extends FormRequest
{
    /**
     * 是否开启验证
     * return true 开启
     * return false 关闭
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'desn' => 'required',
            'body' => 'required'
        ];
    }

    // 自动逸验证错误信息
    public function messages()
    {
        return [
            'title.required' => '标题一定要写'
        ];
    }
}
