<?php

function breadcrumbs(){
	
	if(function_exists('yoast_breadcrumb')){
		yoast_breadcrumb('<p id="breadcrumbs">','</p>');
	} 

}

function render_Terms( $terms ) {
    if( ! empty( $terms ) )
        for($i = 0; $i < count( $terms ); $i++ ): ?>
            <span class="news-section-title"><?php echo $terms[$i]->name; ?></span>
        <?php endfor;
}


