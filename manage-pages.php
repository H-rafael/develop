<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = Typecho_Widget::widget('Widget_Stat');
?>

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
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <div class="clearfix">
                        <?php include 'page-title.php'; ?>
                    </div>
                    <p class="text-sm mb-0">
                    </p>
                </div>
                <div class="table-responsive py-4">
                    <div id="datatable-buttons_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <form method="get">
                            <div class="dt-buttons btn-group">
                                <div class="operate">
                                    <label><i class="sr-only"><?php _e('全选'); ?></i><input type="checkbox" class="typecho-table-select-all" /></label>
                                    <div class="btn-group btn-drop">
                                        <button class="btn dropdown-toggle btn-s form-control-sm" type="button"><i class="sr-only"><?php _e('操作'); ?></i><?php _e('选中项'); ?> </button>
                                        <ul class="dropdown-menu">
                                            <li><a lang="<?php _e('你确认要删除这些页面吗?'); ?>" href="<?php $security->index('/action/contents-page-edit?do=delete'); ?>"><?php _e('删除'); ?></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div id="datatable-buttons_filter" class="dataTables_filter">
                                <div class="search" role="search">
                                    <?php if ('' != $request->keywords): ?>
                                        <a href="<?php $options->adminUrl('manage-pages.php'); ?>"><?php _e('&laquo; 取消筛选'); ?></a>
                                    <?php endif; ?>
                                    <input type="text" class="text-s  form-control form-control-sm" placeholder="<?php _e('请输入关键字'); ?>" value="<?php echo htmlspecialchars($request->keywords); ?>" name="keywords" />
                                    <button type="submit" class="btn btn-s form-control-sm" style="margin-top: -3px;"><?php _e('筛选'); ?></button>
                                </div>
                            </div>
                        </form>
                        <form method="post" name="manage_pages" class="operate-form">
                        <table class="table align-items-center table-flush table-hover typecho-list-table">
                            <thead class="thead-light">
                            <tr class="nodrag">
                                <th> </th>
                                <th> </th>
                                <th><?php _e('标题'); ?></th>
                                <th><?php _e('缩略名'); ?></th>
                                <th><?php _e('作者'); ?></th>
                                <th><?php _e('日期'); ?></th>
                            </tr>

                            </thead>
                            <tbody>
                            <?php Typecho_Widget::widget('Widget_Contents_Page_Admin')->to($pages); ?>
                            <?php if($pages->have()): ?>
                                <?php while($pages->next()): ?>
                                    <tr id="<?php $pages->theId(); ?>">
                                        <td><input type="checkbox" value="<?php $pages->cid(); ?>" name="cid[]"/></td>
                                        <td><a href="<?php $options->adminUrl('manage-comments.php?cid=' . $pages->cid); ?>" class="balloon-button size-<?php echo Typecho_Common::splitByCount($pages->commentsNum, 1, 10, 20, 50, 100); ?>" title="<?php $pages->commentsNum(); ?> <?php _e('评论'); ?>"><?php $pages->commentsNum(); ?></a></td>
                                        <td>
                                            <a href="<?php $options->adminUrl('write-page.php?cid=' . $pages->cid); ?>"><?php $pages->title(); ?></a>
                                            <?php
                                            if ($pages->hasSaved || 'page_draft' == $pages->type) {
                                                echo '<em class="status">' . _t('草稿') . '</em>';
                                            } else if ('hidden' == $pages->status) {
                                                echo '<em class="status">' . _t('隐藏') . '</em>';
                                            }
                                            ?>
                                            <a href="<?php $options->adminUrl('write-page.php?cid=' . $pages->cid); ?>" title="<?php _e('编辑 %s', htmlspecialchars($pages->title)); ?>"><i class="i-edit"></i></a>
                                            <?php if ('page_draft' != $pages->type): ?>
                                                <a href="<?php $pages->permalink(); ?>" title="<?php _e('浏览 %s', htmlspecialchars($pages->title)); ?>"><i class="i-exlink"></i></a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php $pages->slug(); ?></td>
                                        <td><?php $pages->author(); ?></td>
                                        <td>
                                            <?php if ($pages->hasSaved): ?>
                                                <span class="description">
                                <?php $modifyDate = new Typecho_Date($pages->modified); ?>
                                <?php _e('保存于 %s', $modifyDate->word()); ?>
                                </span>
                                            <?php else: ?>
                                                <?php $pages->dateWord(); ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6"><h6 class="typecho-list-table-title"><?php _e('没有任何页面'); ?></h6></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer-html.php';?>
</div>
<?php
include 'common-js.php';
include 'table-js.php';
?>

<?php if(!isset($request->status) || 'publish' == $request->get('status')): ?>
    <script type="text/javascript">
        (function () {
            $(document).ready(function () {
                var table = $('.typecho-list-table').tableDnD({
                    onDrop : function () {
                        var ids = [];

                        $('input[type=checkbox]', table).each(function () {
                            ids.push($(this).val());
                        });

                        $.post('<?php $security->index('/action/contents-page-edit?do=sort'); ?>',
                            $.param({cid : ids}));
                    }
                });
            });
        })();
    </script>
<?php endif; ?>

<?php include 'footer.php'; ?>
