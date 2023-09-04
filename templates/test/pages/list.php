<h2>Категория: <?= $categoryTitle ?> 
<?php if($rights==1 or empty($_GET['category']) or $categoryClass->status==1):?>
    
</h2>
<?php if($rights == 1 and !empty($category)): ?>
    <a href="?page=category_manage&category=<?=$category?>&action=edit">Изменить категорию</a>
<?php endif; ?>
<div>




По умолчанию: <a href="?page=list&category=<?= intval($_GET['category']) ?>&sort=sort&order=asc&search=<?= htmlspecialchars($_GET['q']); ?>"
    <?php if($_GET['sort']=='sort' AND $_GET['order']=='asc' or empty($_GET['sort'])): ?>
                class="sort-selected"
    <?php endif; ?>
    >
    в прямом порядке</a> 
    /
    <a href="?page=list&category=<?= intval($_GET['category']) ?>&sort=sort&order=desc&search=<?= htmlspecialchars($_GET['q']); ?>"
    <?php if($_GET['sort']=='sort' AND $_GET['order']=='desc'): ?>
                class="sort-selected"
    <?php endif; ?>
    >в обратном порядке</a><br> 
По цене: 
<a href="?page=list&category=<?= intval($_GET['category']) ?>&sort=price&order=asc&search=<?= htmlspecialchars($_GET['q']); ?>"
    <?php if($_GET['sort']=='price' AND $_GET['order']=='asc'): ?>
                class="sort-selected"
    <?php endif; ?>
    >
    сначала дешёвые</a> 
    /
    <a href="?page=list&category=<?= intval($_GET['category']) ?>&sort=price&order=desc&search=<?= htmlspecialchars($_GET['q']); ?>"
    <?php if($_GET['sort']=='price' AND $_GET['order']=='desc'): ?>
                class="sort-selected"
    <?php endif; ?>
    >сначала дорогие</a>

<br> 
По рейтингу: 
<a href="?page=list&category=<?= intval($_GET['category']) ?>&sort=rate&order=desc&search=<?= htmlspecialchars($_GET['q']); ?>"
    <?php if($_GET['sort']=='rate' AND $_GET['order']=='desc'): ?>
                class="sort-selected"
    <?php endif; ?>

    >
    популярные
</a>

<br>
По названию: 
<a href="?page=list&category=<?= intval($_GET['category']) ?>&sort=name&order=asc&search=<?= htmlspecialchars($_GET['q']); ?>"
    <?php if($_GET['sort']=='name' AND $_GET['order']=='asc'): ?>
                class="sort-selected"
    <?php endif; ?>
    >
    в прямом порядке</a> 
    /
    <a href="?page=list&category=<?= intval($_GET['category']) ?>&sort=name&order=desc&search=<?= htmlspecialchars($_GET['q']); ?>"
    <?php if($_GET['sort']=='name' AND $_GET['order']=='desc'): ?>
                class="sort-selected"
    <?php endif; ?>
    >в обратном порядке</a>

<h3>Поиск среди категорий:</h3>

<div class="categories-search">
                        <? foreach ($categoriesMain as $main):?>
                                    <?php if($rights == 1 or $main['is_active']==1): ?>
                                    <div class="menu-category-item 
                                        <?php if($main['is_active']==0): ?>
                                            menu-category-item-deleted
                                        <?php endif; ?>
                                    ">
                                        <strong><a class="menu-category-item" href="?page=list&category=<?=$main['id']?>"><?=$main['title']?></a></strong>
                                        <?php if($rights == 1): ?>
                                            <i>(<?=$main['sort']?>)</i>
                                        <?php endif; ?>
                                        <p><?=$main['description']?></p>
                                        <div class="menu-category-sub">
                                            <? if(array_key_exists($main['id'],$categoriesChild)): foreach ($categoriesChild[$main['id']] as $child):?>
                                                <div 
                                                    <?php if($child['is_active']==0): ?>
                                                        class="menu-category-item-deleted"
                                                    <?php endif; ?>
                                                >
                                                <?php if($rights == 1 or $child['is_active']==1): ?>

                                                <strong><a class="menu-category-item
                                                    

                                                " href="?page=list&category=<?=$child['id']?>"><?=$child['title']?></a></strong>
                                                <?php if($rights == 1): ?>
                                                    <i>(<?=$child['sort']?>)</i>
                                                <?php endif; ?>
                                                <p><?=$child['description']?></p>                                    
                                                <?php endif; ?>
                                                </div>    
                                            <? endforeach; endif;?>
                                        </div>
                                           
                                    </div>
                                    <?php endif; ?> 
                                <? endforeach; ?>

</div>

<br>
</div>
<?php if(!empty($_GET['search'])): ?>
    <h3>Всего результатов: <?= $numberResults ?></h3>
<?php endif; ?>
<div class="products" id="products">
<?php
    foreach($products as $product):
?>
    <div class='products-item 
    <?php if($product['is_active']==0): ?>
                products-item-deleted
    <?php endif; ?>
    
    '>
        <div class='products-item-picture'></div>
        <div class='products-item-info'>
            <p><strong><a class="products-item-info-link" href="?page=detail&product=<?=$product['id']?>"><?=$product['name']?></a></strong></p>
            <p><i>#<?=$product['id']?></i></p>
            <p>Цвет: <?=$product['color']?></p>
            <p>Размер: <?=$product['size']?></p>
            <p>Цена: <b><?=$product['price']?>&#36;</b></p>
            <p>Рейтинг: <?=$rate[$product['id']]?></p>
            <?php if(isAuthed()): ?>
                <button class="products-manage-button" onclick="document.location='?page=order&product=<?=$product['id']?>'">Заказать</button>
                <button class="products-manage-button" onclick="document.location='?page=review&product=<?=$product['id']?>'">Отзыв</button><br>
            <?php endif; ?>
            <?php if($rights==1): ?>
                <button class="products-manage-button" onclick="document.location='?page=product_card&action=edit&product=<?=$product['id']?>'">Изменить</button>
                <?php if($product['is_active']==1): ?>
                    <button class="products-manage-button product-delete-button" data-id="<?=$product['id']?>">Удалить</button>
                <?php else: ?>
                    <button class="products-manage-button product-ressurect-button" data-id="<?=$product['id']?>">Восстановить</button>
                <?php endif; ?>
                <div>
                    <button class="products-manage-button product-delete-forever-button" data-id="<?=$product['id']?>"><b>Удалить НАВЕЧНО</b></button>
                </div>   
            <?php endif; ?>

        </div>
    </div>
    <? endforeach; ?>
</div>
<div class="pagination">
    <?php if($from > 1): ?>
        <a href="?page=list&from=<?=$from-1?>" class="pagination-item">&lt;</a>
    <?php endif; ?>
    <?php for($i=0;$i<$numberPages;$i++): ?>
        <a href="?page=list&from=<?=$i+1?>&category=<?= intval($_GET['category']) ?>&sort=<?=$_SESSION['sort']?>&order=<?=$_SESSION['order']?>"
            <?php if($i+1 == $from): ?>
                class="pagination-item-active"
            <?php else: ?>
                class="pagination-item"
            <?php endif; ?>
        ><?=$i+1?></a>
    <?php endfor; ?>
    <?php if($from < $numberPages): ?>
        <a href="?page=list&from=<?=$from+1?>&category=<?= intval($_GET['category']) ?>&sort=<?=$_SESSION['sort']?>&order=<?=$_SESSION['order']?>" class="pagination-item">&gt;</a>
    <?php endif; ?>
</div>
<?php if($removeNextButton!=1): ?>
    <div class="products-next"><a id="products-next-button" class="products-next-button" data-page="<?=$from+1?>" data-category="<?=$category?>">Далее</a></div>
<?php endif; ?>
<?php endif; ?>


