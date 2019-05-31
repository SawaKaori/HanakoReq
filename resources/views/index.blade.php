<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ファイルアップロードのテスト</title>
</head>
<body>
    <h1>ファイルアップロードのテスト</h1>
    <form action="/upload" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="file" name="file">
    <button type="submit">保存</button>
</body>
</html>
