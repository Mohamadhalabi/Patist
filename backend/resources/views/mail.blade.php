{{-- html mail template --}}

<html>
    <head>
        <title>New {{ $type }}</title>
    </head>
    <body>
        <h1>New {{ $type }}</h1>
        <p>Created at : <b>{{ date('Y-m-d H:i:s') }}</b></p>
        <p>Name : <b>{{ $name }}</b></p>
        <p>Email : <b>{{ $email }}</b></p>
        <p>Patent Number : <b>{{ $patentNumber }}</b></p>
    </body>
</html>
