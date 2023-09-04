<div>
<?php if($product['is_active'] == 1 or $rights == 1): ?>
    <div class="detail-img"></div>

    <h2><?=$product['name']?></h2>
    <p>Категория: <strong><?=$product['categoryTitle']?></strong></p>
    <p>Цвет: <?=$product['color']?></p>
    <p>Размер: <?=$product['size']?></p>
    <p>Цена: <b><?=$product['price']?>&#36;</b></p>
    <p>Рейтинг: 
        <?php if(!empty($product['rate'])): ?>
                <?=$product['rate']?>
        <?php else: ?>
                Никто не проголосовал
        <?php endif; ?>

    </p>
    <p>Описание:<br><?=$product['annotation']?></p>
    <?php if($product['is_active'] == 0): ?>
        <h1>УДАЛЕНО</h1>
    <?php endif; ?>
    <?php if(isAuthed()): ?>
        <button class="products-manage-button" onclick="document.location='?page=order&product=<?=$product['id']?>'">Заказать</button>
        <button class="products-manage-button" onclick="document.location='?page=review&product=<?=$product['id']?>'">Отзыв</button> 
    <?php endif; ?>
    <?php if($rights==1): ?>
        <button class="products-manage-button" onclick="document.location='?page=product_card&action=edit&product=<?=$product['id']?>'">Изменить</button>
        <?php if($product['is_active']==1): ?>
                    <button class="products-manage-button product-delete-button" data-id="<?=$product['id']?>">Удалить</button>
                <?php else: ?>
                    <button class="products-manage-button product-ressurect-button" data-id="<?=$product['id']?>">Восстановить</button>
        <?php endif; ?>
    <?php endif; ?>
    <div>
        <button class="products-manage-button product-delete-forever-button" data-id="<?=$product['id']?>"><b>Удалить НАВЕЧНО</b></button>
    </div>          

</div>
<h3 class="review-title">Отзывы</h3>
        <?php  
        foreach($reviews as $review):
            if(empty($reviews)): 
        ?>      
                Нет отзывов
            <?php else: ?>
                <?php if($rights == 1 or $review['is_active']==1): ?>
                <div    
                    <?php if($review['is_active']==0): ?>
                        class="reviews-item-deleted"
                    <?php endif; ?>
                >      
                <p class="review-text">Рейтинг: <b><?=$review['rate']?></b></p>
                <p class="review-text"><i>Дата: <?=$review['date_added']?></i></p>
                <p class="review-text"><?=$review['comment']?></p>
                <?php if($review['userid']==$_SESSION['id'] or $rights == 1): ?>
                    <button class="reviews-manage-button" onclick="document.location='?page=review&action=edit&product=<?=$review['productid']?>'">Изменить</button>
                    <?php if($review['is_active']==1): ?>
                        <button class="reviews-manage-button review-delete-button" data-id="<?=$review['id']?>">Удалить</button>
                    <?php else:?>
                        <button class="reviews-manage-button review-ressurect-button" data-id="<?=$review['id']?>">Восстановить</button>
                    <?php endif; ?>
                    <button class="reviews-manage-button review-delete-forever-button" data-id="<?=$review['id']?>">Удалить НАВЕЧНО</button>
                <?php endif; ?>
                <hr>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
<?php endif; ?>