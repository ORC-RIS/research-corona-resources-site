<?php get_header();

$BASE_URL = get_site_url() . "/forms-and-references/";

$the_query = new WP_Query( array(
    'post_type' => 'forms-and-references',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order'   => 'desc',
    'no_found_rows'=> 'true'
) );

$taxonomies = get_terms( array(
    'taxonomy' => 'forms-and-references_tax',
    'hide_empty' => true,
) );

$form_types = get_terms( array(
    'taxonomy' => 'forms_type_tax',
    'hide_empty' => true,
) );

$forms = array();

foreach($the_query->posts as $post) {

    $m = get_post_meta( $post->ID, 'cgs_attachment', true );
    $file = get_field('file');
    $open_in_new_tab = get_field('open_in_new_tab');
    $terms = wp_get_post_terms( $post->ID, 'forms-and-references_tax' );
    $types = wp_get_post_terms( $post->ID, 'forms_type_tax' );
    if( $file['url'] ) {
        $the_file_url = $file['url'];
    } else {
        $the_file_url = $m;
    }

    $forms[] = array(
        'id' => $post->ID,
        'content' => $post->post_content,
        'title' => $post->post_title,
        'url' => is_string($the_file_url)? $the_file_url : '',
        'open_in_new_tab' => $open_in_new_tab,
        'type' => array_reduce( $types, function( $r, $type ){
            $r[] = $type->name;
            return $r;
        }, array()),
        'terms' => array_reduce( $terms, function( $r, $term ) {
            $r[] = $term->name; // resolves as string not the pushed array, so can't be returned to the reduce function :|
            return $r; // return array
        }, array()),
    );
}

$terms = array();

foreach( $taxonomies as $tax ) {
    $terms[] = array(
        'id' => $tax->term_id,
        'name' => $tax->name,
        'parent' => $tax->parent,
        'slug' => $tax->slug
    );
}

$type_terms = array();

foreach( $form_types as $type ) {
    $type_terms[] = array(
        'id' => $type->term_id,
        'name' => $type->name,
        'parent' => $type->parent,
        'slug' => $type->slug
    );
}


?>
<div class="container mt-5 mb-5">
	<h2 class="heading-underline mb-5">
		Forms and References
	</h2>
	<div class="row">
		<div class="col-lg-3">
			<ul class="normal-list" id="category-list"></ul>
		</div>
        <div class="col-lg-9">
            <?php if(current_user_can('publish_posts')): ?>
                <div class="text-right"><a class="btn btn-primary btn-sm" href="/wp-admin/post-new.php?post_type=forms-and-references">Add a Form</a></div>
            <?php endif; ?>
            <table class="data-table table-sm table-borderless forms-and-references-table">
                <tbody id="data-table-tbody"></tbody>
            </table>
        </div>
    </div>
</div>
<script>
var forms = <?php echo json_encode($forms); ?>;
var terms = <?php echo json_encode($terms); ?>;
var types = <?= json_encode( $type_terms ) ?>;
var domHooks = {
    controls: {
        categoryList: document.getElementById('category-list')
    },
    dataTable: {
        formShowingCount: document.getElementById('form-showing-count'),
        formCount: document.getElementById('form-count'),
        tbody: document.getElementById('data-table-tbody')
    }
};

var store = {
    termsIDDict: {},
    termsNameDict: {},
    termsHashDict: {},
    search: '',
    filter: '',
    sort: 'Category'
};

(function dataBuilding(){
    var i, id, hash;

    for( i = terms.length; i --> 0; ) {
        id = terms[i].id;

        store.termsIDDict[ id ] = terms[i];
        store.termsIDDict[ id ].children = [];
        store.termsIDDict[ id ].active = false;
        store.termsHashDict[ terms[i].slug ] = terms[i];
        store.termsNameDict[ terms[i].name ] = terms[i];
    }
    
	if( window.location.href.split('#') && window.location.href.split('#')[1] ) {
		hash = window.location.href.split('#')[1];
		hash = hash.toLowerCase();
	}

    if( hash ) {
        if( store.termsHashDict[ hash ] )
            store.termsHashDict[ hash ].active = true;
    }

    for( i = terms.length; i --> 0; )
        if( terms[i].parent )
            store.termsIDDict[ terms[i].parent ].children.push( terms[i] );

    return terms = terms.filter(function(term){ return !term.parent });
})();


var filter = (function(){


    function isActiveFilering() {

        for( var termId in store.termsIDDict )
            if( store.termsIDDict[ termId ].active )
                return true;

        return false;
    }


    return {
        forms: function( ) { // Filtering by inclusion

            var filtered;

            if (isActiveFilering()) // Active filter, means at least one filter is checked.
                filtered = forms.filter(function (form) {
                    for (var termId in store.termsIDDict)
                        if (store.termsIDDict[termId].active && form.terms.indexOf(store.termsIDDict[termId].name) > -1)
                            return true;

                    return false;
                });
            else
                filtered = forms;

            if( !( store.filter == 'all' || store.filter == '' ) ) {
                filtered = filtered.filter(function( form ){
                    return form.type.indexOf( store.filter ) > -1;
                });
            }

            if (store.search && store.search.length > 0) {
                var parts = store.search.toLowerCase().trim().split(' ');

                for( var i = parts.length; i --> 0; ) {
                    var part = parts[i];

                    filtered = filtered.filter(function (form) {
                        return form.title.toLowerCase().indexOf(part) > -1
                            || form.content.toLowerCase().indexOf(part) > -1
                            || "".concat(form.terms).toLowerCase().indexOf(part) > -1;
                    });
                }
            }



            return filtered;
        },
        groupItemsByTerm: function( items ) {
            var groupings = {};

            // A filter is active, so we need to group them carefully
            var isActiveFilteringResult = isActiveFilering();

            for( var i = items.length; i --> 0; ) {
                var item = items[i];

                if( item.terms && item.terms.length > 0 )
                    item.terms.map((function( item ){
                        return function( term ){
                            // If have a filter active AND this filter isn't active we escape the loop
                            if( isActiveFilteringResult && !store.termsNameDict[ term ].active )
                                return ;

                            // Initialize any new groups
                            if(! groupings[ term ] )
                                groupings[ term ] = { children: [], items: [] };

                            // Push the item to the group
                            groupings[ term ].items.push( item );
                        }
                    })( item ));
                else {
                    if(! groupings[ 'Assorted' ] ) groupings[ 'Assorted' ] = { children: [], items: [] };

                    groupings[ 'Assorted' ].items.push(item);
                }
            }

            return groupings;
        },
        groupItemsByAlpha: function( items ) {
            var groupings = {};

            groupings['Alphabetical'] = { children: [], items: items };

            return groupings;
        }
    };
})();

var control = (function(){

    /**
     * Delays calls to the same function if called before the delayed time.
     * @param keyBumpDelay
     * @constructor
     */
    function BumpDelay( keyBumpDelay ) {
        this.keyBumpDelay = keyBumpDelay;
        this.delaying = false;
        this.lastDelay = Date.now();
        this.delayTimeoutID = null;
    }
    BumpDelay.prototype.delay = function( callback ) {
        function run( callback ) {
            this.lastDelay = Date.now();
            this.delaying = false;
            callback();
        }
        function soon() {
            var soon = Date.now() - this.lastDelay + this.keyBumpDelay; // Time until next call
            if( soon > 0 )
                return soon;
            return 0;
        }
        if( Date.now() > this.lastDelay + this.keyBumpDelay  ) { // IF the current key press is past the last delay time RUN
            window.clearTimeout( this.delayTimeoutID );
            run.call(this, callback );
        } else if( !this.delaying ) { // IF were are not below the delay time, and were not delaying set up a timed delay
            this.delaying = true;
            this.delayTimeoutID = setTimeout( run.bind(this, callback), soon.call( this ) );
        } else if( this.delaying ) { // IF were are not below the delay time, and we are delaying reset the function to keyBumpDelay
            clearTimeout( this.delayTimeoutID );
            this.delayTimeoutID = setTimeout( run.bind(this, callback), soon.call( this ) );
        }
    };

    var searchBumpDelayer = new BumpDelay( 200 );

    function filterAndRender() {

        var filtered = filter.forms();

        render.tbody( filtered, domHooks.dataTable.tbody );
    }
    return {
        clearFitlers: function() {
            store.search = '';
            store.sort = 'Category';

            Object.keys( store.termsIDDict ).forEach(function( termID ) {
                store.termsIDDict[ termID ].active = false;
            });

            render.controls( domHooks.controls.categoryList );

            filterAndRender();
        },
        filterBy: function( elem ) {
            store.filter = elem.value;

            filterAndRender();
        },
        sortBy: function( elem ) {
            store.sort = elem.value;

            filterAndRender();
        },
        search: function( elem ) {
            store.search = elem.value;

            searchBumpDelayer.delay( filterAndRender );
        },
        toggleTerm: function( elem ) {
			location.hash = store.termsIDDict[ elem.value ].slug;
            store.termsIDDict[ elem.value ].active = !store.termsIDDict[ elem.value ].active;

            filterAndRender();
        },
        render: function() {
            filterAndRender();
        },
        toggleTermByHash: function( hash ) {
            store.termsHashDict[ hash ].active = !store.termsHashDict[ hash ].active;

            filterAndRender();
        }
    };
})();

var render = (function(){

    function renderGroups( groups ) {
        var r = "";

        r += Object.keys( groups ).sort(function( a, b ){
            if( store.termsNameDict[ a ] && store.termsNameDict[ b ] ) {
                var termA = store.termsNameDict[a];
                var termB = store.termsNameDict[b];

                var namea = (termA.parent) ? store.termsIDDict[termA.parent].name + ' - ' + termA.name : termA.name;
                var nameb = (termB.parent) ? store.termsIDDict[termB.parent].name + ' - ' + termB.name : termB.name;

                if (namea > nameb) return 1;
                if (namea < nameb) return -1;
                return 0;
            } else {
                if( a > b ) return 1;
                if( a < b ) return -1;
                return 0;
            }
        }).reduce(function( c, k ){
            var name = k;
            if( store.termsNameDict[ k ] )
                name = (store.termsNameDict[ k ].parent)? store.termsIDDict[ store.termsNameDict[ k ].parent ].name + ' - ' + k: k;
            return c + '<tr><td><h5>' + name + '</h5></td></tr>' +
                renderItems( groups[ k ].items );
        },'');

        return r;
    }

    function renderItems( items ) {
        return items.sort(function( a, b ) {
            a = a.title.toLowerCase();
            b = b.title.toLowerCase();
            if( a > b ) return 1;
            if( a < b ) return -1;
            return 0;
        }).reduce(function(r,form){

            return r +
                '<tr>' +
                    '<td class="form-row">' +
                        '<div><a href="' + form.url + '" ' + ((form.open_in_new_tab)?'target="_blank"':'') + '>' + form.title + '</a></div>' +
                        (( form.content )?'   <p class="description">' + form.content + '</p>':'') +
                    '</td>' +
                <?php if(current_user_can('edit_published_posts')): ?>
                    '<td class="text-right"><a class="btn btn-primary btn-sm" href="/wp-admin/post.php?post=' + form.id + '&action=edit">Edit</a></td>' +
                <?php endif; ?>
                '</tr>';
        },'');
    }


    function renderTermItem( r, term ) {
        return r +
            '<li>' +
                '<label>' +
                    '<input type="checkbox" onchange="control.toggleTerm( this )" value="' + term.id + '"' +
                    (term.active?' checked="checked"':'') +
                    '> ' +
                    term.name +
                    ((term.children && term.children.length)?
                    '<ul class="pl-3 normal-list">' + term.children.reduce(renderTermItem, '') + '</ul>':'') +
                '</label>' +
            '</li>';
    }


    return {
        controls: function( elem ) {
            var r = '';

            r += '<input class="form-control mb-1" type="text" placeholder="Search forms" onkeyup="control.search(this)">';
            r += '' +
            '<select class="form-control" onchange="control.sortBy(this)">' +
                '<option>Alphabetical</option>' +
                '<option selected>Category</option>' +
            '</select>';
            r += '' +
            '<select class="form-control mb-3" onchange="control.filterBy(this)">' +
                '<option selected value="all">Show All</option>' +
                types.reduce(function( r, t ){ return r + '<option>' + t.name + '</option>' },'') +
            '</select>';

            r += terms.reduce(renderTermItem,'');

            if( elem )
                elem.innerHTML = r;

            return r;
        },
        tbody: function( data, elem ){
            var groups = {};

            if( store.sort == 'Alphabetical' )
                groups = filter.groupItemsByAlpha( data ); // { 'name': [array of forms] }
            if( store.sort == 'Category' )
                groups = filter.groupItemsByTerm( data ); // This returns a flat look up { 'name': [array of forms] }

            var r = '';

            if( data.length )
                r += renderGroups( groups );
            else
                r += '<tr><td>No forms matching this criteria</td></tr>';

            if( elem )
                elem.innerHTML = r;

            return r;
        }
    };
})();

$(function(){
    render.controls( domHooks.controls.categoryList );
    render.tbody( forms, domHooks.dataTable.tbody );

    control.render();
})
</script>
<?php get_footer();
