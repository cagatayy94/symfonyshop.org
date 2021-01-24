<?php foreach ($comments as $value): 
    $nameArray = explode(" ", $value['name']); 
    $name = isset($nameArray[0]) ? $nameArray[0] : 'sample';
    $surname = isset($nameArray[1]) ? $nameArray[1] : 'sample';
?>
    <li style="padding: 0">
        <div class="comment">
            <div class="comment-block">
                <span class="comment-by">
                    <span class="comment-rating">
                        <?php for ($i=0; $i < $value['rate'] ; $i++): ?>
                            <i class="fas fa-star text-color-dark mr-1"></i>
                        <?php endfor; ?>
                    </span>
                    <strong class="comment-author text-color-dark"><?php echo $name[0]."***** " .$surname[0]." *****" ?> 
                    <?php if ($value['buyed']): ?>
                        <span class="badge badge-primary badge-xs mb-2">Ürünü Satın Aldı</span>
                    <?php endif; ?>
                    </strong>
                    <span class="comment-date border-right-0 text-color-light-3"><?php $createdAt = new \DateTime($value['created_at']); echo $createdAt->format('d.m.Y H:i:s')  ?></span> 
                </span>
                <p><?php echo $value['comment']; ?></p>
            </div>
        </div>
    </li>
<?php endforeach; ?>