<ul>

<?php if(array_key_exists('price_per_piece', $item) && $item['price_per_piece']) : ?>

    <li class="calculations"><span>Quantity</span> 

        <div class="user-inputs">

            <div>
                <input class="qty" calculator-type="per-piece" input-type="tiles" type="number" id="user-tiles" name="user-tiles" step="<?php echo number_format($batchData['pieces_per_carton']); ?>" min="<?php echo number_format($batchData['pieces_per_carton']); ?>" max="<?php echo $batchData['total_pieces']; ?>" onchange="changeHiddenInput(document.querySelector('.batch-split-price').textContent, this.getAttribute('calculator-type'))" value="<?php echo number_format($batchData['pieces_per_carton']); ?>">
                <label for="user-sqm">Total Tiles</label>
            </div>

            <svg fill="#000000" height="24px" width="24px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                viewBox="0 0 477.427 477.427" xml:space="preserve"><g><polygon points="101.82,187.52 57.673,143.372 476.213,143.372 476.213,113.372 57.181,113.372 101.82,68.733 80.607,47.519 
                    0,128.126 80.607,208.733 	"/><polygon points="396.82,268.694 375.607,289.907 420,334.301 1.213,334.301 1.213,364.301 420,364.301 375.607,408.694 
                    396.82,429.907 477.427,349.301 	"/></g></svg>
            
            <div>
                <input class="qty" calculator-type="per-piece" input-type="cartons" type="number" id="user-tiles-cartons" name="user-tiles-cartons" min="1" max="<?php echo $item['stock']; ?>" onchange="changeHiddenInput(document.querySelector('.batch-split-price').textContent, this.getAttribute('calculator-type'))" value="1">
                <label for="user-boxes">QTY (boxes)</label>
            </div>
            
        </div>  
        
        </li>

        <div class="max-qty-available">We have <?php echo $item['stock']; ?> <?php echo $batchData['slabclass']== "slabs" ? "slabs" : "boxes"; ?> available within this batch.</div>

    <li class="product-price" title="Includes VAT at <?php echo $batchData['vat_rate']; ?>">
        <span>Price (Inc. VAT)</span>
        <div class="split-price-inner">
        <div class="tile-data-inner">
        <div class="split-total-value">&pound;<?php echo $batchData['total_price']; ?></div>
        <div class="cartons-selected"> (<span class="box-qty">1</span> <?php echo $batchData['slabclass']== "slabs" ? "slabs" : "boxes"; ?>)</div>
        </div>
    </li>

<?php else: ?>
    
    <li>
        <span>M² per <?php echo $batchData['slabclass'] == "slabs" ? "Slab" : "Box"; ?></span>
        <div class="tile-data">
            <div style="display:none" class="cartons-available"><?php echo $item['stock']; ?></div> 
            <div class="tile-data-inner"><div class="sqm-per-carton"><?php echo $batchData['carton_sqm']; ?></div> m²</div>
        </div>
    </li>

    <li class="calculations"><span>Select Quantity</span> 

    <div class="user-inputs">

        <div class="qty-wrapper">
            <label for="user-sqm">Number of m²</label>
            <div class="input-controls">
                <span class="qty-btns minus">-</span>
                <input class="qty" calculator-type="sqm" input-type="sqm" type="number" id="user-sqm" name="user-sqm" step="<?php echo $batchData['carton_sqm']; ?>" min="<?php echo $batchData['carton_sqm']; ?>" max="<?php echo $batchData['sqm_available']; ?>" value="<?php echo $batchData['carton_sqm']; ?>">
                <span class="qty-btns plus">+</span>
            </div>
        </div>

        <svg fill="#bdbbb2" height="24px" width="24px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
            viewBox="0 0 477.427 477.427" xml:space="preserve"><g><polygon points="101.82,187.52 57.673,143.372 476.213,143.372 476.213,113.372 57.181,113.372 101.82,68.733 80.607,47.519 
                0,128.126 80.607,208.733 	"/><polygon points="396.82,268.694 375.607,289.907 420,334.301 1.213,334.301 1.213,364.301 420,364.301 375.607,408.694 
                396.82,429.907 477.427,349.301 	"/></g></svg>
        
        <div class="qty-wrapper">
            <label for="user-boxes">Number of <?php echo $batchData['slabclass'] == "slabs" ? "slabs" : "boxes"; ?></label>
            <div class="input-controls">
                <span class="qty-btns minus">-</span>
                <input class="qty" calculator-type="sqm" input-type="cartons" type="number" id="user-sqm-cartons" name="user-sqm-cartons" min="1" max="<?php echo $item['stock']; ?>" step="1" value="1">
                <span class="qty-btns plus">+</span>
            </div>
        </div>

    </div>
    
    </li>

    <div class="max-qty-available">We have <?php echo $item['stock']; ?> <?php echo $batchData['slabclass']== "slabs" ? "slabs" : "boxes"; ?> available within this batch.</div>

    <li class="product-price" title="Includes VAT at <?php echo $batchData['vat_rate']; ?>">
        <span>Price (Inc. VAT)</span>
        <div class="split-price-inner">

        <div class="tile-data-inner">
            <div class="split-total-value">
            &pound;<?php echo $batchData['total_price']; ?>
            </div>
            <div class="cartons-selected"> (<span class="box-qty">1</span> <?php echo $batchData['slabclass']== "slabs" ? "slabs" : "boxes"; ?>)</div>
        
        </div>
    </li>

<?php endif; ?>     

</ul> 

<div class="product-buttons">
<?php echo shortcode_exists('cptwooint_cart_button') ? do_shortcode( "[cptwooint_cart_button/]" ) : '' ; ?>
<?php if(array_key_exists('price_per_piece', $item) && $item['price_per_piece'] && $batchData['discount_percentage']) : ?>
<div class="sticker sticker-saving">
    <div class="sticker-price sticker-price-saving sticker-price-saving-piece">
    <span>SAVE</span>£<?php echo $batchData['saving_per_tile']; ?>
    </div>
</div>

<?php elseif(array_key_exists('price_per_sqm', $item) && $item['price_per_sqm'] && $batchData['discount_percentage']) : ?>

<div class="sticker sticker-saving">
    <div class="sticker-price sticker-price-saving sticker-price-saving-sqm">
    <span>SAVE</span>£<?php echo $batchData['sqmsaving']; ?>
    </div>
</div>

<?php endif; ?>
