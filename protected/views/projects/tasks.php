<?php echo CHtml::link('Create New Task', $this->createUrl('/tasks/save?Tasks[project_id]=' . $project->id), array('class' => 'btn btn-primary pull-right')); ?>
<div class="clearfix"></div>
<h1>View Tasks for Project: <?php echo $project->name; ?></h1>

<div id="listView">
<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$model->search(),
    'itemView'=>'_tasks',
    'ajaxUpdate'=>false,
    'template'=>"{items}\n{pager}",
    'pager'=>array(
        'header'=>'',   // text before it "Go to page:"
        'htmlOptions'=>array(
            'class'=>'paginator',
        )
    ),

));
?>
</div>

<?php if ($model->search()->totalItemCount > $model->search()->pagination->pageSize): ?>

    <!--<p id="loading" style="display:none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/loading.gif" alt="" /></p>-->
    <button id="loading" style="display:none" class="btn btn-lg btn-warning center-block"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</button>

    <div class="container">
        <div class="span12">
            <p id="showMore" class="btn btn-primary center-block">Show more...</p>
        </div>
    </div>

    <script type="text/javascript">
        /*<![CDATA[*/
        (function($)
        {
            // скрываем стандартный навигатор
            $('.paginator').hide();

            // запоминаем текущую страницу и их максимальное количество
            var page = parseInt('<?php echo (int)Yii::app()->request->getParam('page', 1); ?>');
            var pageCount = parseInt('<?php echo (int)$model->search()->pagination->pageCount; ?>');

            var loadingFlag = false;

            $('#showMore').click(function()
            {
                // защита от повторных нажатий
                if (!loadingFlag)
                {
                    // выставляем блокировку
                    loadingFlag = true;

                    // отображаем анимацию загрузки
                    $('#loading').show();

                    $.ajax({
                        type: 'post',
                        url: window.location.href,
                        data: {
                            // передаём номер нужной страницы методом POST
                            'page': page + 1,
                            '<?php echo Yii::app()->request->csrfTokenName; ?>': '<?php echo Yii::app()->request->csrfToken; ?>'
                        },
                        success: function(data)
                        {
                            // увеличиваем номер текущей страницы и снимаем блокировку
                            page++;
                            loadingFlag = false;

                            // прячем анимацию загрузки
                            $('#loading').hide();

                            // вставляем полученные записи после имеющихся в наш блок
                            $('#listView').append(data);

                            // если достигли максимальной страницы, то прячем кнопку
                            if (page >= pageCount)
                                $('#showMore').hide();
                        }
                    });
                }
                return false;
            })
        })(jQuery);
        /*]]>*/
    </script>

<?php endif; ?>