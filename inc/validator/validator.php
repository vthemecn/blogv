<?php

/**
 * Validator 
 * @link https://github.com/davidecesarano/validator
 */

class Validator
{
    /**
     * @var array $patterns
     */
    public $patterns = array(
        'uri'           => '[A-Za-z0-9-\/_?&=]+',
        'url'           => '[A-Za-z0-9-:.\/_?&=#]+',
        'alpha'         => '[\p{L}]+',
        'words'         => '[\p{L}\s]+',
        'alphanum'      => '[\p{L}0-9]+',
        'int'           => '[0-9]+',
        'float'         => '[0-9\.,]+',
        'tel'           => '[0-9+\s()-]+',
        'text'          => '[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+',
        'file'          => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+\.[A-Za-z0-9]{2,4}',
        'folder'        => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+',
        'address'       => '[\p{L}0-9\s.,()°-]+',
        'date_dmy'      => '[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}',
        'date_ymd'      => '[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}',
        'email'         => '[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+[.]+[a-z-A-Z]'
    );

    /**
     * @var array $errors
     */
    public $errors = array();

    /**
     * 字段名称
     * 
     * @param string $name
     * @return this
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 字段的值
     * 
     * @param mixed $value
     * @return this
     */
    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * 文件
     * 
     * @param mixed $value
     * @return this
     */
    public function file($value)
    {
        $this->file = $value;
        return $this;
    }

    /**
     * 需要验证的正则表达式的
     * 
     * @param string $name
     * @return this
     */
    public function pattern($name)
    {
        if ($name == 'array') {
            if (!is_array($this->value)) {
                $this->errors[] = $this->name . '格式不正确';
            }
        } else {
            $regex = '/^(' . $this->patterns[$name] . ')$/u';
            if ($this->value != '' && !preg_match($regex, $this->value)) {
                $this->errors[] = $this->name . '格式不正确';
            }
        }
        return $this;
    }

    /**
     * 自定义正则表达式
     * 
     * @param string $pattern
     * @return this
     */
    public function customPattern($pattern)
    {
        $regex = '/^(' . $pattern . ')$/u';
        if ($this->value != '' && !preg_match($regex, $this->value)) {
            $this->errors[] = $this->name . '格式不正确';
        }
        return $this;
    }

    /**
     * 必填字段
     * 
     * @return this
     */
    public function required()
    {
        if ((isset($this->file) && $this->file['error'] == 4) || ($this->value == '' || $this->value == null)) {
            $this->errors[] = $this->name . '是必须的';
        }
        return $this;
    }

    /**
     * 验证最小值
     * 
     * @param int $min
     * @return this
     */
    public function min($length)
    {
        if($this->value==''){
            return $this;
        }
        
        if (is_string($this->value)) {
            if (mb_strlen($this->value, 'utf-8') <= $length) {
                $this->errors[] = $this->name . '的长度需要大于' . $length;
            }
        } else {
            if ($this->value <= $length) {
                $this->errors[] = $this->name . '的数值需要大于' . $length;
            }
        }
        return $this;
    }

    /**
     * 验证最大值
     * 
     * @param int $max
     * @return this
     */
    public function max($length)
    {
        if($this->value==''){
            return $this;
        }
        
        if (is_string($this->value)) {

            if (mb_strlen($this->value, 'utf-8') >= $length) {
                $this->errors[] = $this->name . '的值长度需要小于' . $length;
            }
        } else {

            if ($this->value >= $length) {
                $this->errors[] = $this->name . '的值需要小于' . $length;
            }
        }
        return $this;
    }

    /**
     * 验证相等
     * 
     * @param mixed $value
     * @return this
     */
    public function equal($value)
    {
        if ($this->value != $value) {
            $this->errors[] = $this->name . '的值不匹配';
        }
        return $this;
    }

    /**
     * 验证文件大小
     *
     * @param int $size
     * @return this 
     */
    public function maxSize($size)
    {

        if ($this->file['error'] != 4 && $this->file['size'] > $size) {
            $this->errors[] = 'Il file ' . $this->name . ' supera la dimensione massima di ' . number_format($size / 1048576, 2) . ' MB.';
        }
        return $this;
    }

    /**
     * 验证文件扩展名
     *
     * @param string $extension
     * @return this 
     */
    public function ext($extension)
    {

        if ($this->file['error'] != 4 && pathinfo($this->file['name'], PATHINFO_EXTENSION) != $extension && strtoupper(pathinfo($this->file['name'], PATHINFO_EXTENSION)) != $extension) {
            $this->errors[] = 'Il file ' . $this->name . ' non è un ' . $extension . '.';
        }
        return $this;
    }

    /**
     * 防止XSS攻击
     *
     * @param string $string
     * @return $string
     */
    public function purify($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * 检查是否有错误信息
     * 
     * @return boolean
     */
    public function isSuccess()
    {
        if (empty($this->errors)) return true;
    }

    /**
     * 列出所有错误
     * 
     * @return array $this->errors
     */
    public function getErrors()
    {
        if (!$this->isSuccess()) return $this->errors;
    }

    /**
     * 整数验证
     *
     * @param mixed $value
     * @return boolean
     */
    public static function is_int($value)
    {
        if (filter_var($value, FILTER_VALIDATE_INT)) return true;
    }

    /**
     * 浮点数
     *
     * @param mixed $value
     * @return boolean
     */
    public static function is_float($value)
    {
        if (filter_var($value, FILTER_VALIDATE_FLOAT)) return true;
    }

    /**
     * 是否是一个字母
     *
     * @param mixed $value
     * @return boolean
     */
    public static function is_alpha($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z]+$/")))) return true;
    }

    /**
     * 是否是字母或者数字
     *
     * @param mixed $value
     * @return boolean
     */
    public static function is_alphanum($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")))) return true;
    }

    public static function is_url($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) return true;
    }

    public static function is_uri($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[A-Za-z0-9-\/_]+$/")))) return true;
    }

    public static function is_bool($value)
    {
        if (is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) return true;
    }

    public static function is_email($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) return true;
    }

    // 是否手机号
    public static function is_mobile($var): bool
    {
        return !!preg_match("/^1[3-9][0-9]{9}$/", $var);
    }
}
