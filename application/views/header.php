<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- 
        ## File 이용방법
        
        1.루트 디렉토리에 css,js,img 디렉토리를 생성
        2.그안의 파일 경로를 링크하면 적용됨
        3.application 디렉토리에 넣지 말것!
     -->
    <link rel="stylesheet" href="/static/css/style.css">
    <title>Document</title>
</head>
<body>
<?php
var_dump($this->config->item('base_url'));
?>


<?php if($this->config->item('is_dev')){ ?>
    <div>
        개발환경을 수정 중입니다.
    </div>
<?php } ?>