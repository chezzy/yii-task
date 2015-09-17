<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$model->search(),
    'itemView'=>'_tasks',
    'ajaxUpdate'=>false,
    'template' => "{items}",
)); ?>