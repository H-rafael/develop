<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$phpMaxFilesize = function_exists('ini_get') ? trim(ini_get('upload_max_filesize')) : 0;

if (preg_match("/^([0-9]+)([a-z]{1,2})$/i", $phpMaxFilesize, $matches)) {
    $phpMaxFilesize = strtolower($matches[1] . $matches[2] . (1 == strlen($matches[2]) ? 'b' : ''));
}

Typecho_Widget::widget('Widget_Contents_Attachment_Edit')->to($attachment);
?>
<style>
    .typecho-option li input,textarea{
        width: 100%;
    }
    .edit-media{
        margin-top: -27px;
    }
    .card-text{
        margin-top: -9px!important;
    }
</style>

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">管理文章</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">首页</a></li>
                            <li class="breadcrumb-item active" aria-current="page">管理文章</li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-lg-6">
            <div class="card-wrapper">
                <div class="card">
                    <!-- Card image -->
                    <?php if ($attachment->attachment->isImage): ?>
                        <img class="card-img-top" src="<?php $attachment->attachment->url(); ?>" alt="<?php $attachment->attachment->name(); ?>">
                    <?php endif; ?>



                    <!-- Card body -->
                    <div class="card-body">
                        <p  class="card-text mt-4">
                            <?php $mime = Typecho_Common::mimeIconType($attachment->attachment->mime); ?>
                            <i class="mime-<?php echo $mime; ?>"></i>
                            <a href=""><strong><?php $attachment->attachment->name(); ?></strong></a>
                            <span><?php echo number_format(ceil($attachment->attachment->size / 1024)); ?> Kb</span>
                        </p>

                        <p  class="card-text mt-4">
                            <input id="attachment-url" type="text" class="mono w-100" value="<?php $attachment->attachment->url(); ?>" readonly />
                        </p>

                        <div id="upload-panel" class="p card-text mt-4">
                            <div class="upload-area " style="font-size: .85rem;" draggable="true"><?php _e('拖放文件到这里<br>或者 %s选择文件上传%s', '<a href="###" class="upload-file">', '</a>'); ?></div>
                            <ul id="file-list"></ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card-wrapper">
                <div class="card">
                    <!-- Card header -->
                    <div id="edit-secondary" class="" role="complementary">
                        <ul class="typecho-option-tabs clearfix card-header">
                            <?php include 'page-title.php'; ?>
                        </ul>


                        <div id="tab-advance" class="tab-content card-body">
                            <div class="col-mb-12 col-tb-12 edit-media" role="form">
                                <?php $attachment->form()->render(); ?>
                            </div>
                        </div><!-- end #tab-advance -->

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
?>
<script src="<?php $options->adminStaticUrl('js', 'moxie.js?v=' . $suffixVersion); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'plupload.js?v=' . $suffixVersion); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#attachment-url').click(function () {
        $(this).select();
    });
    
    $('.operate-delete').click(function () {
        var t = $(this), href = t.attr('href');

        if (confirm(t.attr('lang'))) {
            window.location.href = href;
        }

        return false;
    });

    $('.upload-area').bind({
        dragenter   :   function () {
            $(this).parent().addClass('drag');
        },

        dragover    :   function (e) {
            $(this).parent().addClass('drag');
        },

        drop        :   function () {
            $(this).parent().removeClass('drag');
        },
        
        dragend     :   function () {
            $(this).parent().removeClass('drag');
        },

        dragleave   :   function () {
            $(this).parent().removeClass('drag');
        }
    });

    function fileUploadStart (file) {
        $('<ul id="file-list"></ul>').appendTo('#upload-panel');
        $('<li id="' + file.id + '" class="loading">'
            + file.name + '</li>').prependTo('#file-list');
    }

    function fileUploadError (error) {
        var file = error.file, code = error.code, word; 
        
        switch (code) {
            case plupload.FILE_SIZE_ERROR:
                word = '<?php _e('文件大小超过限制'); ?>';
                break;
            case plupload.FILE_EXTENSION_ERROR:
                word = '<?php _e('文件扩展名不被支持'); ?>';
                break;
            case plupload.FILE_DUPLICATE_ERROR:
                word = '<?php _e('文件已经上传过'); ?>';
                break;
            case plupload.HTTP_ERROR:
            default:
                word = '<?php _e('上传出现错误'); ?>';
                break;
        }

        var fileError = '<?php _e('%s 上传失败'); ?>'.replace('%s', file.name),
            li, exist = $('#' + file.id);

        if (exist.length > 0) {
            li = exist.removeClass('loading').html(fileError);
        } else {
            $('<ul id="file-list"></ul>').appendTo('#upload-panel');
            li = $('<li>' + fileError + '<br />' + word + '</li>').prependTo('#file-list');
        }

        li.effect('highlight', {color : '#FBC2C4'}, 2000, function () {
            $(this).remove();
        });
    }

    function fileUploadComplete (id, url, data) {
        var img = $('.typecho-attachment-photo');

        if (img.length > 0) {
            img.get(0).src = '<?php $attachment->attachment->url(); ?>?' + Math.random();
        }
        
        $('#' + id).html('<?php _e('文件 %s 已经替换'); ?>'.replace('%s', data.title))
        .effect('highlight', 1000, function () {
            $(this).remove();
            $('#file-list').remove();
        });
    }

    var uploader = new plupload.Uploader({
        browse_button   :   $('.upload-file').get(0),
        url             :   '<?php $security->index('/action/upload?do=modify&cid=' . $attachment->cid); ?>',
        runtimes        :   'html5,flash,html4',
        flash_swf_url   :   '<?php $options->adminStaticUrl('js', 'Moxie.swf'); ?>',
        drop_element    :   $('.upload-area').get(0),
        filters         :   {
            max_file_size       :   '<?php echo $phpMaxFilesize ?>',
            mime_types          :   [{'title' : '<?php _e('允许上传的文件'); ?>', 'extensions' : '<?php $attachment->attachment->type(); ?>'}],
            prevent_duplicates  :   true
        },
        multi_selection :   false,

        init            :   {
            FilesAdded      :   function (up, files) {
                plupload.each(files, function(file) {
                    fileUploadStart(file);
                });

                uploader.start();
            },

            FileUploaded    :   function (up, file, result) {
                if (200 == result.status) {
                    var data = $.parseJSON(result.response);

                    if (data) {
                        fileUploadComplete(file.id, data[0], data[1]);
                        return;
                    }
                }

                fileUploadError({
                    code : plupload.HTTP_ERROR,
                    file : file
                });
            },

            Error           :   function (up, error) {
                fileUploadError(error);
            }
        }
    });

    uploader.init();
});
</script>
<?php
include 'footer.php';
?>