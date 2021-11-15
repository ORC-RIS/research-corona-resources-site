<?php get_header();

    $the_query = new WP_Query( array(
        'post_type' => 'faq',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order'   => 'desc',
        'no_found_rows'=> 'true',
        'tax_query'         => array(
            array(
                'taxonomy' => 'faq_tax',
                'operator' => 'NOT IN',
                'field'    => 'slug',
                'terms'    => array( 'covid' ),
            ),
        ),
    ) );


	$terms = find_all_terms('faq_tax');

    $taxonomies = get_terms( array(
        'taxonomy' => 'faq_tax',
        'hide_empty' => true,
    ) );

    $items = array();

    foreach($the_query->posts as $post) {

        $m = get_post_meta( $post->ID, 'cgs_attachment', true );
        $terms = wp_get_post_terms( $post->ID, 'faq_tax' );


        $items[] = array(
            'id' => $post->ID,
            'content' => wpautop($post->post_content),
            'title' => $post->post_title,
			'slug' => get_post_field( 'post_name', $post->ID ),
            'url' => ( is_array( $m ) && array_key_exists('url', $m ) )? $m['url'] : '',
            'terms' => array_reduce( $terms, function( $r, $term ) {
                $r[] = $term->name; // resolves as string not the pushed array, so can't be returned to the reduce function :|
                return $r; // return array
            }, array()),
        );
    }
?>
<style>
    #questions-hook h3 {
        margin: 10px;
    }
    #questions-hook a.collapser {
        color: black;
        border-bottom: 1px solid lightgray;
        display: block;
        padding: 10px;
    }
    #questions-hook a.collapser i {
        margin-top: 3px;
        margin-right: 10px;
    }
    #questions-hook a.collapser.collapsed i:before {
        content: "\f061";
    }
    #questions-hook a.collapser:hover {
        text-decoration: none;
    }

    .faq-content {
        background: rgba(0,0,0,.1);
        padding: 30px;
    }
</style>

<script>
    var given = {
        terms: <?php echo json_encode($terms); ?>,
        items: <?php echo json_encode($items); ?>
    }
</script>
<div class="container mt-5 mb-5">
    <h2 class="heading-underline mb-5">
        FAQ
    </h2>
    <div class="row">
        <div class="col-lg-12">
            <?php if(current_user_can('publish_posts')): ?>
                <div class="text-right"><a class="btn btn-primary btn-sm" href="/wp-admin/post-new.php?post_type=faq">Add FAQ</a></div>
            <?php endif; ?>
            <div id="questions-hook" data-children=".item"></div>
        </div>
    </div>
</div>
<!----

function short_code_frequently_asked_question( $faq_post ) {
<div class="d-flex mb-4 flex-column">
    <a href="<?= get_permalink( $faq_post->ID ) ?>" class="ucf-faq-question-link d-flex collapsed" data-toggle="collapse" data-target="#post<?= $faq_post->ID ?>" aria-expanded="false">
        <div class="ucf-faq-collapse-icon-container mr-2 mr-md-3">
            <span class="ucf-faq-collapse-icon" aria-hidden="true"></span>
        </div>
        <h3 class="ucf-faq-question align-self-center mb-0 mt-0"><?= $faq_post->post_title ?></h3>
    </a>
    <div class="ucf-faq-topic-answer ml-2 ml-md-3 mt-2 collapse" id="post<?= $faq_post->ID ?>" aria-expanded="false">
        <div class="card">
            <div class="card-block"><?= $faq_post->post_content ?></div>
        </div>
    </div>
</div>

}

---->

<script>
    var store = {
        faqs: [],
        faqsByGroup: {}
    };

    var hooks = {
        questions: document.getElementById('questions-hook')
    };

    var sort = {
        byTitle: function(a,b) {a=a.title.toLowerCase();b=b.title.toLowerCase(); if(a>b)return 1; if(a<b)return-1;return 0;}
    };


    (function initialize(){
        store.faqs = given.items;

        for( var i = 0, l = given.items.length; i < l; i++ ) {
            var item = given.items[i];

            if( item.terms.length )
                item.terms.forEach(function( term ) {
                    if( !store.faqsByGroup[ term ] )
                        store.faqsByGroup[ term ] = [];

                    store.faqsByGroup[term].push( item );
                });
            else {
                if( !store.faqsByGroup[ 'General' ] )
                    store.faqsByGroup[ 'General' ] = [];

                store.faqsByGroup['General'].push( item );
            }
        }

    })();

    var faqModule = (function(){

        var faqNum = 0;

        function renderRow( r,  item, i ) {

			var contentDiv = document.createElement('div');

			contentDiv.innerHTML = item.content;

            r +=

            '<div class="d-flex mb-4 flex-column">' +
                '    <a name="' + item.slug + '" href="#faq' + faqNum + '" onclick="history.replaceState(undefined, undefined, \'#' + item.slug + '\' )" class="ucf-faq-question-link d-flex collapsed" data-toggle="collapse" data-target="#post'+ faqNum +'" aria-expanded="false">' +
                '        <div class="ucf-faq-collapse-icon-container mr-2 mr-md-3">' +
                '            <span class="ucf-faq-collapse-icon" aria-hidden="true"></span>' +
                '        </div>' +
                '        <h3 class="ucf-faq-question align-self-center mb-0 mt-0">'+
                item.title +
                '        </h3>' +
                '    </a>' +
                '    <div class="ucf-faq-topic-answer ml-2 ml-md-3 mt-2 collapse" id="post' + faqNum + '" aria-expanded="false">' +
                '        <div class="card">' +
                '            <div class="card-block">' +
                contentDiv.innerHTML +
                <?php if(current_user_can('edit_published_posts')): ?>
                '<div class="text-right"><a class="btn btn-primary btn-sm" href="/wp-admin/post.php?post=' + item.id + '&action=edit">Edit</a></div>' +
                <?php endif; ?>
                '</div>' +
                '        </div>' +
                '    </div>' +
                '</div>';

            faqNum++;

            return r;
        }


        var publicFunctions = {};


        publicFunctions.controls = {
            selectCategory: function( elem ){

                publicFunctions.render( hooks.questions, store.faqsByGroup, elem.value );
            }
        };

        publicFunctions.render = function( hook, data, category ) {
            var r = "";
            var groups;

            groups = Object.keys( store.faqsByGroup ).sort();

            if( category && groups.indexOf( category ) > -1 )
                groups = category;

            groups.forEach(function( group ){
                r += '<a name="' + group.replace(/\s/g, "-").toLowerCase() + '"></a><h5>' + group + '</h5>';
                r = store.faqsByGroup[ group ].sort( sort.byTitle ).reduce( renderRow, r );
            });

            if( hook )
                return hook.innerHTML = r;
            else
                return r;
        };

        return publicFunctions;
    })();

	function scrollToAnchor() {
        var hash, elementQ, offset;

		if (window.location.href.indexOf('#') > -1) {
			hash = window.location.href.split('#')[1];

			if(/^[a-z0-9\-]*$/i.test(hash)) {
			    var foundName = false;

			    $( '.faq-anchor' ).each(function( i, anchor ){
			        var name = $( anchor ).attr('name');
			        if( name === hash )
                        foundName = name;
                });
                if( foundName ) {
                    elementQ = $("a[name='" + foundName + "']");
                    elementQ.click();

                    offset = elementQ.offset();

                    $('html').scrollTop(offset.top - 150);
                }
            }
		}
	}


    $(function(){
        faqModule.render( hooks.questions, store.faqs );
        setTimeout(scrollToAnchor, 100);
    });
</script>
<?php get_footer();
