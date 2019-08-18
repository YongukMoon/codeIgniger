<form action="index.php/opentutorials/Topic/add" method="POST" class="span10">
    <?php echo validation_errors(); ?>
    <input type="text" name="title" placeholder="제목" class="span12" />
    <textarea name="description" class="span12" cols="30" rows="10" placeholeder="본문"></textarea>
    <input type="submit" value="btn">
</form>

<script src="https://cdn.ckeditor.com/ckeditor5/12.3.1/classic/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description', {
        'filebrowserUploadUrl':'/index.php/topic/upload_receive_from_ck'
    });
</script>