<?php
include 'common.php';
include 'header.php';
include 'menu.php';

Typecho_Widget::widget('Widget_Themes_Files')->to($files);
?>

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">设置外观</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">首页</a></li>
                            <li class="breadcrumb-item active" aria-current="page">设置外观</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="row ">
        <div class="main col">
            <div class="body container card">


                <div class="card-header">
                    <ul class="typecho-option-tabs fix-tabs clearfix">
                        <li><a href="<?php $options->adminUrl('themes.php'); ?>"><?php _e('可以使用的外观'); ?></a></li>
                        <li class="current"><a href="<?php $options->adminUrl('theme-editor.php'); ?>">
                                <?php if ($options->theme == $files->theme): ?>
                                    <?php _e('编辑当前外观'); ?>
                                <?php else: ?>
                                    <?php _e('编辑%s外观', ' <cite>' . $files->theme . '</cite> '); ?>
                                <?php endif; ?>
                            </a></li>
                        <?php if (Widget_Themes_Config::isExists()): ?>
                            <li><a href="<?php $options->adminUrl('options-theme.php'); ?>"><?php _e('设置外观'); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </div>


                <div class="row typecho-page-main" role="main">

                    <div class="typecho-edit-theme" style="width:100%;margin-top: 19px;">

                        <div class="col-mb-12 col-tb-8 col-9 content">
                            <?php include 'page-title.php'; ?>

                            <form method="post" name="theme" id="theme" action="<?php $security->index('/action/themes-edit'); ?>">
                                <label for="content" class="sr-only"><?php _e('编辑源码'); ?></label>
                                <textarea name="content" id="content" class="w-100 mono" <?php if(!$files->currentIsWriteable()): ?>readonly<?php endif; ?>><?php echo $files->currentContent(); ?></textarea>
                                <p class="submit">
                                    <?php if($files->currentIsWriteable()): ?>
                                        <input type="hidden" name="theme" value="<?php echo $files->currentTheme(); ?>" />
                                        <input type="hidden" name="edit" value="<?php echo $files->currentFile(); ?>" />
                                        <button type="submit" class="btn primary"><?php _e('保存文件'); ?></button>
                                    <?php else: ?>
                                        <em><?php _e('此文件无法写入'); ?></em>
                                    <?php endif; ?>
                                </p>
                            </form>
                        </div>
                        <ul class="col-mb-12 col-tb-6 col-3">
                            <li><strong>模板文件</strong></li>
                            <?php while($files->next()): ?>
                                <li<?php if($files->current): ?> class="current"<?php endif; ?>>
                                    <a href="<?php $options->adminUrl('theme-editor.php?theme=' . $files->currentTheme() . '&file=' . $files->file); ?>"><?php $files->file(); ?></a></li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer-html.php';?>
</div>



<?php
include 'common-js.php';
Typecho_Plugin::factory('admin/theme-editor.php')->bottom($files);
include 'footer.php';
?>