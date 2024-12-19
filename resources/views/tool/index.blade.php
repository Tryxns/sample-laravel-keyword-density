@extends('layouts.master')

@section('content')
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="html-text-tab" data-bs-toggle="tab" data-bs-target="#html-text-tab-pane" 
        type="button" role="tab" aria-controls="html-text-tab-pane" aria-selected="true">HTML or Text</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="url-tab" data-bs-toggle="tab" data-bs-target="#url-tab-pane" 
        type="button" role="tab" aria-controls="url-tab-pane" aria-selected="false">URL</button>
    </li>
</ul>
<form id="keywordDensityInputForm" method="POST">
    <div class="form-group ">
        <div class="tab-content py-3 " id="myTabContent" style="min-height: 350px;">
            <div class="tab-pane fade show active" id="html-text-tab-pane" role="tabpanel" aria-labelledby="html-text-tab" tabindex="0">
                <textarea class="form-control" id="keywordDensityTextArea" rows="12"></textarea>
            </div>
            <div class="tab-pane fade" id="url-tab-pane" role="tabpanel" aria-labelledby="url-tab" tabindex="0">
                <input class="form-control" type="text" id="keywordDensityURL" name="keywordDensityURL"/>
            </div>
        </div>
        
    </div>
    <button type="submit" class="btn btn-primary mb-2">Get Keyword Densities</button>
</form>
<div id="keywordDensityResult">

</div>

@endsection

@section ('scripts')
    <script>
        $(".nav-link").click(function(){
            $("#keywordDensityTextArea, #keywordDensityURL").val("");
        });
        $('#keywordDensityInputForm').on('submit', function (e) {
            e.preventDefault();
            let textInput = $('#keywordDensityTextArea').val();
            let urlInput = $('#keywordDensityURL').val();

            if (textInput !== "" || urlInput !== "") {
                // Set CSRF token up with ajax.
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({ // Pass data to backend
                    type: "POST",
                    url: "/tool/calculate-and-get-density",
                    data: {'textInput': textInput, 'urlInput':urlInput},
                    success: function (response) {
                        // On Success, build a data table with keyword and densities
                        if (response.length > 0) {
                            let html = "<table class='table'><tbody><thead>";
                            html += "<th>Keyword</th>";
                            html += "<th>Count</th>";
                            html += "<th>Density</th>";
                            html += "</thead><tbody>";

                            for (let i = 0; i < response.length; i++) {
                                html += "<tr><td>"+response[i].keyword+"</td>";
                                html += "<td>"+response[i].count+"</td>";
                                html += "<td>"+response[i].density+"%</td></tr>";
                            }

                            html += "</tbody></table>";

                            $('#keywordDensityResult').html(html); // Append the html table after the form.
                        }
                    },
                });
            }
        })
    </script>
@endsection