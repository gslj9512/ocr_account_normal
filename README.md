# ocr_account_normal
户口本识别（目前仅集成了百度接口）

#### 使用方法

只能传入图片的base64字符串，具体参照 [百度api文档](https://cloud.baidu.com/doc/OCR/s/ak3h7xzk7 "百度api文档")
```
    <?php
    use Ocr\action\Baidu;
    use Ocr\lib\Baidu as Lib;
		
    $lib = new Lib([
    	'client_id'=>'',
        'client_secret'=>''
    ]);
    $ocr = new Baidu($lib);
    $img_base_64 = '图片base_64编码';
    $res = $ocr->accountBook($img_base_64);
    var_dump($res);
