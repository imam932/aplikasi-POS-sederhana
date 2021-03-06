
@section('script')
<script>
$('document').ready(function(){
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    // select2 js config
    $('#period').select2({
        allowclear: true
    });

    // config lightbox js
    lightbox.option({
        'disableScrolling': true,
        'showImageNumberLabel': false,
    })

    // config datatable for total penjualan
    $('#data-table').on('draw.dt', function() {
        let intVal, total, pageTotal;

        intVal = function(i) {
            return typeof i === "string" ?
            i.replace(/[\$,]/g, '') * 1 :
            typeof i === "number" ?
            i : 0;
        }

        total = table.column(6).data().reduce( function(a, b) {
                    a = convertToAngka(a);
                    b = convertToAngka(b);
            return (intVal(a)) + (intVal(b));
        }, 0);

        pageTotal = table.column(6, {page: 'current'}).data().reduce(function (a, b) {
            return intVal(a) + intVal(b);
        }, 0);

        $('.total').text(convertToRupiah(total));
    });

    table = $("#data-table").DataTable({
            responsive: true,
            processing : true,
            serverSide : true,
            ajax: {
                url: "{{ route('report.penjualan.index') }}",
                type: "GET",
                data: function(d) {
                    d.filter = $('#period').val();
                }
            },
            columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'penjualan_kode',
                name: 'penjualan_kode'
            },
            {
                data: 'member.member_nama',
                name: 'member.member_nama',
                orderable: false,
                searchable: false
            },
            {
                data: 'penjualan_detail[0].total',
                name: 'penjualan_detail[0].total',
                orderable: false,
                searchable: false
            },
            {
                data: 'tanggal',
                name: 'tanggal'
            },
            {
                data: 'user.name',
                name: 'user.name',
                orderable: false,
                searchable: false
            },
            {
                data: 'penjualan_total',
                name: 'penjualan_total'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
            ],
            "oLanguage" :
            {
                "sSearch" : "Pencarian",
                "oPaginate" :
                    {
                        "sNext" : "Berikutnya",
                        "sPrevious" : "Sebelumnya",
                        "sFirst" : "Awal",
                        "sLast" : "Akhir",
                        "sEmptyTable" : "Data tidak ditemukan!"
                    }
            }
        });

    $('body').on('click', '#report-detail', function(e) {
        e.preventDefault();

        let id = $(this).attr('data');

        $.ajax({
            url: 'penjualan/'+id,
            method: 'GET',
            dataType: 'JSON',
            cache: false,
            success: function(data)
            {
                let i, gtotal;

                $('#report-body-table').find('.row-report-table').remove();
                $('#report-pembeli-nama').text(data.member.member_nama);
                $('#report-pembeli-alamat').text(data.member.member_alamat);
                $('#report-pembeli-phone').text(data.member.member_phone);
                $('#report-penjualan-kode').text('No. Transaksi #'+data.penjualan_kode);
                $('#report-penjualan-tanggal').text(data.tanggal);
                $('#report-penjualan-nama').text(data.user.name);

                for(i=0; i < data.penjualan_detail.length; i++)
                {
                    $('#report-body-table').append(
                        '<tr class="row-report-table">'+
                        '<td>'+data.penjualan_detail[i].produk.produk_kode+'</td>'+
                        '<td>'+data.penjualan_detail[i].produk.produk_nama+'</td>'+
                        '<td>'+data.penjualan_detail[i].detailpenjualan_qty+'</td>'+
                        '<td>'+convertToRupiah(data.penjualan_detail[i].produk.produk_jual)+'</td>'+
                        '<td class="text-right">'+convertToRupiah(data.penjualan_detail[i].detailpenjualan_subtotal)+'</td>'+
                        '</tr>'
                    );
                }

                $('#report-penjualan-total').text(convertToRupiah(data.penjualan_total));
            }
        });

        $('#reportModal').modal('show');
    });

    $('body').on('change', '#period', function(e) {
        e.preventDefault();
        table.draw();
    });

    $('body').on('click', '#toPDF', function(e) {
        e.preventDefault();

        let filter = $('#period').val();
        console.log(filter);
        $.ajax({
            url: 'penjualan/toPDF',
            method: 'POST',
            data: {filter:filter},
            dataType: 'JSON',
            success: function(data)
            {
                console.log(data);
            }
        })
    })
});
</script>
@endsection
