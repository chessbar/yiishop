<link rel="stylesheet" href="assets/admin/css/compiled/user-list.css" type="text/css" media="screen" />
        <!-- main container -->
        <div class="content">
            <div class="container-fluid">
                <div id="pad-wrapper" class="users-list">
                    <div class="row-fluid header">
                        <h3>商品列表</h3>
                        <div class="span10 pull-right">
                            <a href="/index.php?r=admin%2Fproduct%2Fadd" class="btn-flat success pull-right">
                                <span>&#43;</span>添加新商品</a></div>
                    </div>
                    <!-- Users table -->
                    <div class="row-fluid table">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="span6 sortable">
                                        <span class="line"></span>商品名称</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>商品库存</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>商品单价</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>是否热卖</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>是否促销</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>促销价</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>是否上架</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>是否推荐</th>
                                    <th class="span4 sortable align-right">
                                        <span class="line"></span>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($products as $product):?>
                                <tr class="first">
                                    <td>
                                        <img src="<?php echo $product->cover;?>-coversmall" class="img-circle avatar hidden-phone" />
                                        <a href="<?php echo yii\helpers\Url::to(['/product/detail','productid'=>$product->id]);?>" target="_blank" class="name"><?php echo $product->title;?></a></td>
                                    <td><?php echo $product->num;?></td>
                                    <td><?php echo $product->price;?></td>
                                    <td><?php echo $product->ishot ? '热卖' : '不热卖';?></td>
                                    <td><?php echo $product->issale ? '促销' : '不促销';?></td>
                                    <td><?php echo $product->saleprice;?></td>
                                    <td><?php echo $product->isshelve=="1" ? '上架' : '下架';?></td>
                                    <td><?php echo $product->isrecommend ? '推荐' : '不推荐';?></td>
                                    <td class="align-right">
                                        <a href="<?php echo yii\helpers\Url::to(['product/mod','productid'=>$product->id]);?>">编辑</a>
                                        <a href="<?php echo yii\helpers\Url::to(['product/on','productid'=>$product->id]);?>">上架</a>
                                        <a href="<?php echo yii\helpers\Url::to(['product/off','productid'=>$product->id]);?>">下架</a>
                                        <a href="<?php echo yii\helpers\Url::to(['product/del','productid'=>$product->id]);?>">删除</a></td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pull-right">
                        <?php echo yii\widgets\LinkPager::widget(['pagination'=>$pager]);?>
                    </div>
            </div>
        </div>
        <!-- end main container -->