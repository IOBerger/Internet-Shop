    <?php if(!empty($_GET['product'])): ?>
    <fieldset class="review-field">
        <div class="legend">Будем рады увидеть ваш отзыв!</div>
            <form id="review-form" action="?page=review" method="post" name="review">
                <div class="main">
                    <div class="form-row">
                        <input type="hidden" id="id" value="<?= $review['id'] ?>">
                        <label for="product">
                            Товар
                        </label>
                        <p><?=$product['name']?></p>
                        <input type="hidden" id="product" name="product" value="<?=$product['id']?>" disabled>     
                    </div>
                    <div class="form-row">
                        <label for="rate">
                            Рейтинг
                        </label>
                        <select id="rate" name="rate" required="required">
                        <option 
                                value="5" 
                                <?php if($review['rate'] == 5): ?>
                                    selected
                                <?php endif; ?>
                            >
                                5 - отлично!
                            </option>
                            <option 
                                value="4" 
                                <?php if($review['rate'] == 4): ?>
                                    selected
                                <?php endif; ?>
                            >
                                4 - хорошо
                            </option>
                            <option 
                                value="3" 
                                <?php if($review['rate'] == 3): ?>
                                    selected
                                <?php endif; ?>
                            >
                                3 - удовлетворительно
                            </option>
                            <option 
                                value="2" 
                                <?php if($review['rate'] == 2): ?>
                                    selected
                                <?php endif; ?>
                            >
                                2 - неудовлетворительно
                            </option>
                            <option 
                                value="1" 
                                <?php if($review['rate'] == 1): ?>
                                    selected
                                <?php endif; ?>
                            >
                                1 - кошмар
                            </option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="comment">
                            Отзыв
                        </label>
                        <textarea id="comment" name="comment" required="required"><?= $review['comment'] ?></textarea>
                    </div>
                    <div class="form-row">
                        <input class="submit-button" type="submit" value="Отправить">
                    </div> 
                </div>
            </form>
    </fieldset>
<?php endif; ?>    
<h3 class="review-title">Ваши отзывы</h3>
<?php if(empty($reviews)): ?>
                Нет отзывов
<?php else: ?>
<?php  
        foreach($reviews as $review):
?>
<div 
    <?php if($review['status']==0): ?>
                class="reviews-item-deleted"
    <?php endif; ?>
>
            <p class="review-text">Рейтинг: <b><?=$review['rate']?></b></p>
            <p class="review-text">Продукт: <b><?=$review['name']?></b></p>
            <p class="review-text"><i>Дата: <?=$review['review_date']?></i></p>
            <p class="review-text"><?=$review['comment']?></p>
            <button class="reviews-manage-button" onclick="document.location='?page=review&action=edit&product=<?=$review['productid']?>'">Изменить</button>
            <?php if($review['status']==1): ?>
                <button class="reviews-manage-button review-delete-button" data-id="<?=$review['reviewid']?>">Удалить</button>
            <?php else:?>
                <button class="reviews-manage-button review-ressurect-button" data-id="<?=$review['reviewid']?>">Восстановить</button>
            <?php endif; ?>
            <button class="reviews-manage-button review-delete-forever-button" data-id="<?=$review['reviewid']?>">Удалить НАВЕЧНО</button>
            <hr>
</div>
<?php endforeach; ?>
<?php endif; ?>
