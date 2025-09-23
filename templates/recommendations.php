<?php
/**
 * Template Name: Recommendations (Plugin)
 */
use EsRecommendations\ESRE_Compare;

get_header();

$compare = new ESRE_Compare();
$items   = $compare->display_recommendations();
?>
<main id="primary" class="site-main recommendations-page">
<?php if ( empty( $items ) ) : ?>
    <p>No valid products selected for comparison.</p>
<?php else : ?>
    <div class="category-products">
        <ul class="product-list container productloading">
            <?php foreach ( $items as $index => $item ) : ?>
                <li 
                    data-id="<?php echo get_the_ID(); ?>"
                    data-discount-rate="<?php echo round($item['batch']['discount_percentage'], 0); ?>% Off"
                    data-menu-order="<?php echo get_post_field( 'menu_order', get_the_ID()); ?>" 
                    data-default-order="<?= $i; ?>" 
                    data-price-order="<?php echo $item['batch']['price_per_sqm'] ?>"

                    class="featured mix <?php echo $item['batch']['job_lot_class']; ?> <?php echo $item['batch']['cat_effect_classes'] ?>">

                    <a href="<?php the_permalink(); ?>">

                        <div class="swatch-container">

                            <?php if($item['gallery']) : ?>

                                <?php if(is_array($item['gallery']['src'])) : ?>
                                    
                                    <?php if(count($item['gallery']['src']) > 1) : // if more than 1 gallery image 
                                    
                                        if($swatches_first) :  // if swatches first toggle ?>
                                                
                                            <?php if($i < 4) {  // loop the first three items to prioritise image loading ?>
                                                    
                                                <img class="swatch" width="500" height="500" alt="close up tile image" src="<?php echo $item['gallery']['src'][0]; ?>" decoding="async" fetchpriority="high" loading="eager">
                                                
                                                <img class="swatch" width="500" height="500" alt="lifestyle swatch image" src="<?php echo $item['gallery']['src'][1]; ?>" decoding="sync" fetchpriority="low" loading="lazy">
                                                                            
                                                <?php  } else {  // loop the remaining items to low prioritise image loading  ?>
                                                                                        
                                                <img class="swatch" width="500" height="500" alt="close up tile image" src="<?php echo $item['gallery']['src'][0]; ?>" decoding="sync" fetchpriority="low" loading="lazy">
                                                
                                                <img class="swatch" width="500" height="500" alt="lifestyle swatch image" src="<?php echo $item['gallery']['src'][1]; ?>" decoding="sync" fetchpriority="low" loading="lazy">
                                                                                        
                                                <?php } // end image loading priority ?>
                                        
                                        <?php else: // if ambients first toggle ?>
                                        
                                            <?php if($i < 4) {  // loop the first three items to prioritise image loading ?>
                                                    
                                                <img class="swatch" width="500" height="500" alt="close up tile image" src="<?php echo $item['gallery']['src'][1]; ?>" decoding="async" fetchpriority="high" loading="eager">
                                                
                                                <img class="swatch" width="500" height="500" alt="lifestyle swatch image" src="<?php echo $item['gallery']['src'][0]; ?>" decoding="sync" fetchpriority="low" loading="lazy">
                                                                            
                                                <?php  } else { // loop the remaining items to low prioritise image loading  ?>
                                                                                        
                                                <img class="swatch" width="500" height="500" alt="close up tile image" src="<?php echo $item['gallery']['src'][1]; ?>" decoding="sync" fetchpriority="low" loading="lazy">
                                                
                                                <img class="swatch" width="500" height="500" alt="lifestyle swatch image" src="<?php echo $item['gallery']['src'][0]; ?>" decoding="sync" fetchpriority="low" loading="lazy">
                                                                                        
                                                <?php }  // end image loading priority ?>
                                        
                                        <?php endif; ?>
                                    
                                    <?php elseif(count($item['gallery']['src']) === 1) : // if only one galery image ?>

                                        <img class="swatch" width="500" height="500" alt="lifestyle swatch image" src="<?php echo $item['gallery']['src'][0]; ?>" decoding="async" fetchpriority="low" loading="lazy">
                                        <img class="swatch" width="500" height="500" alt="close up tile image" src="<?php echo $item['gallery']['src'][0]; ?>" decoding="async" fetchpriority="low" loading="lazy">
                                    
                                    <?php endif; ?>
                                    
                                <?php else: // if no images exist ?>

                                    <img class="swatch" width="500" height="500" alt="swatch placeholder image" src="/wp-content/uploads/placeholder-1.png'; ?>" decoding="async" fetchpriority="low" loading="lazy">
                                    <img class="swatch" width="500" height="500" alt="swatch placeholder image" src="/wp-content/uploads/placeholder-2.png'; ?>" decoding="async" fetchpriority="low" loading="lazy">

                                <?php endif; ?>

                            <?php endif; ?>

                        </div>
                    </a>

                    <a href="<?php the_permalink(); ?>">
                        
                        <img class="swatchspacer" width="500" height="500" alt="swatch spacer" src="/wp-content/themes/emporio/images/tile-swatch-square.png">
                        
                        <figcaption><?php echo $item['effect']; ?></figcaption>
                        
                        <ul class="product-list-attributes">
                            <li class="joblotnumber">ID:<?php echo $item['batch']['joblot_number']; ?></li>
                            <li class="product-title"><?php echo $item['batch']['factory_name']; ?> <?php echo get_the_title(); ?><?php if(!empty($item['batch']['decor'])) : echo ' ' . $item['batch']['decor']; endif; ?><?php if($item['batch']['finish']) : echo ' - ' . $item['batch']['finish']; endif; ?></li>
                            <li><?php echo $item['batch']['size']; ?> mm</li>
                            <?php 

                            // Test if current item is price per piece / decor
                            if(array_key_exists('price_per_piece', $item['batch']) && $item['batch']['price_per_piece']) : ?>

                                <?php if($item['batch']['job_lot_class'] === 'joblot') : ?>

                                    <li><?= ($item['stock'] * $item['batch']['pieces_per_carton']); ?> tiles / <?php echo $item['stock']; ?> boxes (<?php echo number_format($item['batch']['pieces_per_carton'], 0, '', ''); ?> tiles per box)</li>
                                    <li class="product-price"><div class="">Price per tile: &pound;<?php echo $item['batch']['price_per_piece']; ?></div></li>
                                    <li><div class="price-details">&pound;<?= $item['batch']['discount_percentage'] ? number_format((($item['discounted_carton_price'] * $item['stock']) * $item['vatRate']), 2, '.', '')  : number_format((($item['single_carton_price'] * $item['stock']) * $item['vatRate']), 2, '.', ''); ?></div></li>

                                <?php else: ?>

                                    <li><?php echo $item['stock']; ?> boxes (<?php echo $item['batch']['pieces_per_carton']; ?> tiles per box)</li>
                                    <li class="product-price"><div class="price-details">Price per tile: &pound;<?php echo $item['batch']['price_per_piece']; ?></div></li>

                                <?php endif; ?>

                            <?php else: ?>

                            <li class="product-price">&pound;<?php echo $item['batch']['price_per_sqm'];  ?> per m² <?php if(!$item['batch']['split_price']): ?> (<?php echo $item['sqm']; ?> m² available)<?php else : ?>(Good stock levels)<?php endif; ?></li>                    
                            
                            <?php if(empty($item['batch']['split_price'])): ?>
                            
                            <li>
                                <div class="price-details">
                                    
                                    &pound;<?php echo $item['batch']['discount_percentage'] ? number_format((($item['discounted_carton_price'] * $item['stock']) * $item['vatRate']), 2, '.', '')  : number_format((($item['single_carton_price'] * $item['stock']) * $item['vatRate']), 2, '.', ''); ?>

                                    <!-- <?php if(!empty($item['batch']['discount_percentage'])) : ?>

                                        <div class="discounted-price">(includes <?php echo $item['batch']['discount_percentage']; ?>% discount)</div>

                                    <?php endif; ?> -->
                                    
                                    </div>
                            </li>
                                    
                                <?php endif; ?>

                            

                            <?php endif; ?>
                            
                        </ul>
                    </a>
                    <a class="wishlist-icon" title="Add to wishlist"><i class="fa-regular fa-heart"></i></a>

                    <?php if(! array_key_exists('decor', $item['batch'])) : ?>
                        <a class="sample-icon" title="Order free sample">
                            <i class="fa-solid fa-swatchbook"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if(array_key_exists('price_per_piece', $item['batch']) && $item['batch']['price_per_piece'] && $item['batch']['discount_percentage']) : ?>
                        
                        <div class="sticker sticker-saving fadeIn-animation">
                            <div class="sticker-price sticker-price-saving sticker-price-saving-piece">
                                <span>SAVE</span>£<?php echo $item['batch']['saving_per_tile']; ?>
                            </div>
                        </div>
                        
                    <?php elseif($item['batch']['price_per_sqm'] && $item['batch']['discount_percentage']) : ?>

                        <div class="sticker sticker-saving fadeIn-animation">
                            <div class="sticker-price sticker-price-saving sticker-price-saving-sqm">
                                <span>SAVE</span>£<?php echo $item['batch']['sqmsaving']; ?>
                            </div>
                        </div>

                    <?php endif; ?>
                        
                    <?php if($item['gallery']) : ?>

                        <input type="hidden" name="p" <?php echo $item['batch']['inputValues']; ?> p-img="<?php echo $item['gallery']['src'][0]; ?>">

                    <?php else: ?>

                        <input type="hidden" name="p" <?php echo $item['batch']['inputValues']; ?> p-img="">

                    <?php endif; ?>

                </li>

                <?php $i++; ?>

                <?php wp_reset_postdata(); ?>
            <?php endforeach; ?>
            <li class="gap"></li><li class="gap"></li><li class="gap"></li>
        </ul>
    </div>
<?php endif; ?>
</main>
<?php get_footer(); ?>


