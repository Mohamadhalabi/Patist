@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 offset-4 text-center mt-2">
            <h2>Data List</h2>
            <form>
                <select id="selectType" class="form-control">
                    <option value="token">Tokens</option>
                    <option value="query">Queries</option>
                    <option value="service">Services</option>
                    <option value="service-fee">Service Fees</option>
                    <option value="official-fee">Official Fees</option>
                </select>
                <button class="btn btn-primary mt-2" style="min-width:200px" id="submit">Select</button>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid bg-light p-3 mt-3" id="response" style="display:none">
    <div class="row">
        <div class="col-md-12" id="result">
        </div>
    </div>
</div>
@endsection

@section('script')
<script>

    const submit = document.getElementById('submit');
    
    $('#submit').click(function(e){
        e.preventDefault();
        const selectType = document.getElementById('selectType').value;
        $.ajax({
            url: '/api/v1/query/'+selectType+'/data-list',
            type: 'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiODdmOGE3NThiYzdlNjM1MDFhMGU0MTQ4ODJhMzAxOTMyNzAxOTM0NGUzMTI5OWRmODc5NjgzYTAzMGU2N2Q5YTc5ZDBmYzgxYWEzNmNmY2IiLCJpYXQiOjE2NTUyNzk1MjMuMjIwMDA0LCJuYmYiOjE2NTUyNzk1MjMuMjIwMDA4LCJleHAiOjE2ODY4MTU1MjMuMDc0MDI5LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.VmZRUooPkACBnLLUO8uYChHbqJDVo-JxSYYfJRz7PXa9mHWst9JlttnZW4bit-GmuebsZav6pGq3dfs-BWB2Il55ClkqK3_dQRPAttYQnsMpAT4gLqTEtoyrlHN4ktfjX7Ah78U0pFlIvdwVhAQ6aPUMVnv7nGp3HvYNvzyehKXg4CznIr53S3SmbhXF1l9VgU6qnFuilepNNMVq5Z0uQLE4uK1Fe5GasBPq7QPUCBzrgT2NaXZE9IfZU3L_VXNgtwYlKFVRfpyTf9o4ivSgLFJJ2mwcm1IPAPcCvbEP1EGyLc5FLFoRcdqRETVPwd1wxUpctCO2GEqVrB9CGp32RG7rmuPkUVTilQRUY-cmt4JGfSHHdiHMTSqdHNUaJl1gC0Je295DU6VmrzPPHd9j38aex44NPfVDRsaUF5RF7jAi3ndbTXZ9DjUe7PvQHNpl4Gu53sKKFcaqsY4Kgt2QAl-93_H8c6E4syVkymrWVk7LvAZvAb2EkOjSlb3UwPk6JRfHOFjYofhccDKnBiKUZwPY8HbOwNapowVGjk-AjBwD_ZJyYMtpXCs5XWZDSsGYQ7Q6UV-vdWimRLVyffHIQYsUYZ3RYJut1TYi9C0yxRg95-zlTmY2rPWi6ut8mSXlTaQrMjsn62yjbGnVkONBqzaxER6Fxu2d5xhsswDi1eg');
            },
            success: function(data){
                var jsonPretty = JSON.stringify(JSON.parse(data),null,2);
                $('#result').html("<pre>"+jsonPretty+"</pre>");
                $('#response').show();
            },
            error: function(data){
                $('#info').html("error");
            },
        });
})

</script>
@endsection