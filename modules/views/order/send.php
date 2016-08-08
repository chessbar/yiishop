<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="content">
    <div class="container-fluid">
        <div id="pad-wrapper" class="new-user">
            <div class="row-fluid header">
                <h3>发货</h3></div>
            <div class="row-fluid form-wrapper">
                <!-- left column -->
                <div class="span9 with-sidebar">
                    <div class="container">
                        <?php
                            if(Yii::$app->session->hasFlash('info')){
                                echo Yii::$app->session->getFlash('info');
                            }
                        ?>
                        <?php $form =ActiveForm::begin([
                            'options'=>['class'=>'new_user_form inline-input'],
                            'fieldConfig'=>['template'=>"<div class='form-group field-order-expressno required'>{label}{input}</div>{error}"]
                        ]);
                        echo $form->field($model,'expressno')->textInput(['class'=>'span9']);
                        ?>


                            <div class="span11 field-box actions">
                                <?php echo Html::submitButton('发货',['class'=>'btn-glow primary']);?>
                                <span>OR</span>
                                <?php echo Html::resetButton('取消',['class'=>'btn-glow default']);?>
                            </div>
                        <?php ActiveForm::end();?>
                    </div>
                </div>
                <!-- side right column -->
                <div class="span3 form-sidebar pull-right">
                    <div class="alert alert-info hidden-tablet">
                        <i class="icon-lightbulb pull-left"></i>请在左侧表单当中填写快递单号</div>
                    <h6>快递单号说明</h6>
                    <p>填写快递单号</p>
                </div>
            </div>
        </div>
    </div>
</div>