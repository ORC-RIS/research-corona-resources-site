<?php

function theme_deck_datatable( $atts = array() ) {

    $a = shortcode_atts( array(
        'class' => '',
        'style' => '',
        'option' => '',
        'columns' => '',
        'allowfootsearch' => '',
        'SearchBar' => '',
    ), $atts );

    $columns = explode( ',', $a['columns'] );

    $style = '';

    $class = '';
    $class .= $a['class'];

    $file = get_field( $a['option'] );
    $_dataTableID++;
?>
<section class="<?= $class ?>" style="<?= $style ?>">
    <div class="pull-right text-right mb-3">
        <a class="btn btn-primary btn-sm" href="<?= $file['url'] ?>"><i class="fas fa-file-excel"></i> Download Excel</a>
    </div>
    <style>
        table.dataTable.stripe tbody tr.odd, table.dataTable.display tbody tr.odd {
            background: none;
        }
        .fundingLink-color {
            font-weight: 600;
        }
    </style>
    <table id="datatable-<?= $_dataTableID ?>" class="display compact stripe table table-striped table-bordered" style="margin-top: 6px;">
        <thead>
        <tr>
            <?php foreach( $columns as $column ): ?>
            <th><?= $column ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <?php if( $a['allowfootsearch'] === "true") { ?>
            <tfoot>
            <tr>
                <?php foreach( $columns as $column ): ?>
                    <th><?= $column ?></th>
                <?php endforeach; ?>
            </tr>
            </tfoot>
        <?php } ?>
    </table>
    <script>
        function showMoreAction( el ) {
            var $this = $( el );
            var $last = $this.prev();
            $last.toggle();
            if( $last.css('display') == 'none' )
                $this.text('Show more (' + ($last.children().length) + ')' );
            else
                $this.text('Show fewer' );
        }

        var url = "<?= $file['url'] ?>";
        var oReq = new XMLHttpRequest();
        var ourDataTable;
        function loadDataTables() {
            oReq.open("GET", url, true);
            oReq.responseType = "arraybuffer";

            oReq.onload = function (e) {
                var arraybuffer = oReq.response;

                /* convert data to binary string */
                var data = new Uint8Array(arraybuffer);
                var arr = [];
                for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
                var bstr = arr.join("");

                /* Call XLSX */
                var workbook = XLSX.read(bstr, {
                    type: "binary"
                });

                /* Get first worksheet name */
                var first_sheet_name = workbook.SheetNames[0];

                /* Get worksheet */
                var worksheet = workbook.Sheets[first_sheet_name];

                var range = XLSX.utils.decode_range(worksheet['!ref']);
                for (var R = range.s.r; R <= range.e.r; ++R) {
                    for (var C = range.s.c; C <= range.e.c; ++C) {
                        var coord = XLSX.utils.encode_cell({
                                r: R,
                                c: C
                            }),
                            cell = worksheet[coord];
                        if (!cell || !cell.v) continue;
                        // clean up raw value of string cells
                        if (cell.t == 's') cell.v = cell.v.trim();
                        // clean up formatted text
                        if (cell.w) {
                            if (cell.l) {
                                cell.w = '<a class="fundingLink-color" target="_blank" href="' + cell.l.Target + '">' +
                                    cell.v.trim() + ' </a>';
                            } else {
                                cell.w = cell.w.trim();
                            }
                        }
                    }
                }

                //COVID-19 HPC Consortium

                var data = XLSX.utils.sheet_to_json(worksheet, {
                    raw: false,
                    defval: ''
                });


                function showMore() {
                    var columns = $('#datatable-<?= $_dataTableID ?> tbody tr td:nth-child(5)');
                    for( var i = 0; i < columns.length; i++ ) {
                        let column = $(columns[i]);
                        var text = column.html();
                        if( text.match(/\<span/i) )
                            continue;


                        var textParts = text.split(';');
                        for( var j = 0; j < textParts.length; j++ )
                            textParts[j] = textParts[j].trim();

                        textParts = textParts.sort(function( a,b){
                            var an = a.toLowerCase(), bn = b.toLowerCase();
                            if( an < bn ) return -1;
                            if( an > bn ) return 1;
                            return 0;
                        });

                        var maxLength = 10;
                        var firstPart = textParts.slice(0, maxLength-1);
                        var r = '';
                        for( var j = 0; j < firstPart.length; j++ )
                            r += '<span class="badge badge-pill badge-primary mr-1">' + firstPart[j] + '</span>';

                        if( textParts.length > maxLength ) {
                            var secondPart = textParts.slice( maxLength, textParts.length );

                            r += '<span style="display: none">';
                            for( var j = 0; j < secondPart.length; j++ )
                                r += '<span class="badge badge-pill badge-primary mr-1">' + secondPart[j] + '</span>';
                            r += '</span><a class="badge badge-pill badge-info" onclick="showMoreAction( this )">Show more (' + (secondPart.length) + ')</a>'
                        }

                        column.html( r );
                    }
                }


                ourDataTable = $('#datatable-<?= $_dataTableID ?>').DataTable(<?= get_field("datatable_configuration") ?>).on('draw', showMore);

                showMore();

            };



            oReq.send();
        }
        loadDataTables();

        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "currency-pre": function ( a ) {
                console.log(a);
                if( a === 'Not Specified')
                    a = "0";
                a = a.replace( /[\,\sUSD]/g, "" );
                return parseFloat( a );
            },

            "currency-asc": function ( a, b ) {
                console.log(a,b);
                return a - b;
            },

            "currency-desc": function ( a, b ) {
                console.log(a,b);
                return b - a;
            }
        } );
        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "deadline-pre": function ( a ) {
                console.log(a);
                if( a.trim() === 'Continuous Submission')
                    a = Date.now()*2;
                else if( a.trim() === 'None Posted')
                    a = 0;
                a = (new Date(a)).getTime();
                return a;
            },

            "deadline-asc": function ( a, b ) {
                return a - b;
            },

            "deadline-desc": function ( a, b ) {
                return b - a;
            }
        } );
    </script>
</section>
<?php
}


add_shortcode('deck_datatable', function ( $atts = array(), $content = null ) {
    ob_start();

    theme_deck_datatable( $atts, $content );

    return ob_get_clean();

});

?>