jQuery(function() {

    load_content();
});
// $(document).ready(function($) {
//     $(".table-row").on('click', function() {
//         alert('sad');
//         // window.document.location = $(this).data("href");
//     });
// });

function detail(id){
    
    window.document.location = laroute.route('admin.contact.detail', { id: id });
}

function load_content(page = 1)
{

    var kategori = $('#kategori').val();
    var keyword = $('#keyword').val();
    
    $.ajax({
        url: laroute.route('admin.contact'),
        type: "GET",
        dataType: "JSON",
        data: {
            keyword: keyword,
            kategori_id : kategori,
            page: page,
        },
        beforeSend: function(){
            $('#data-list tbody tr#loading').removeClass('d-none');
        },
        success: function(response) {
            if(!response.next_page)
            {
                $('#nextProduk').prop('disabled', true);
            }else{
                $('#nextProduk').prop('disabled', false);
            }
            if(!response.prev_page)
            {
                $('#prevProduk').prop('disabled', true);
            }else{
                $('#prevProduk').prop('disabled', false);
            }if(response.current_page == 1)
            {
                $('#dataContent').html('');
                $('#btn-loadMore').removeClass('d-none');
            } 

            if(response.data.length !== 0)
            {
                var i = 0;
                $.each(response.data, function(k, v) {
                    $('#data-list tbody').append(`
                    <tr class="c-pointer" onclick="detail(`+ response.data[k].id +`)">
                        <td width="3%">
                            <div class="custom-control custom-checkbox mb-5">
                                <input class="custom-control-input" type="checkbox" name="example-checkbox1" id="example-checkbox1" value="option1" >
                                <label class="custom-control-label" for="example-checkbox1"></label>
                            </div>
                        </td>
                        <td>
                            <div class="font-size-16 font-w600">`+ response.data[k].name +`</div>
                            <div class="font-size-15">` + response.data[k].profession +`</div>
                        </td>
                        <td>
                            <div class="font-size-16 font-w600">` + response.data[k].email +`</div>
                            <div class="font-size-15">` + response.data[k].phone +`</div>
                        </td>
                        <td>
                            <div class="font-size-16 font-w600">` + response.data[k].subject +`</div>
                            <div class="font-size-15">` + response.data[k].category +`</div>
                        </td>
                        <td>
                        ` + response.data[k].status_badge +`
                        </td>
                        <td>
                        ` + response.data[k].dibuat +`
                        </td>
                    </tr>                    
                    `);
                });
                $('#data-list tbody tr#loading').addClass('d-none');
            }else{
                $('#btn-loadMore').addClass('d-none');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
        }
    });
}
