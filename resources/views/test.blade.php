@extends('layouts.app')
@section('content')
    <div class="container">
             
        <div class="row">
            <div class="col-md-12">
             <form action="http://it-office-dev.khnpp.ua/test" method="POST">
                @csrf
            <label>Ім’я: <input type="text" name="name" required></label><br>
            <label>Email: <input type="email" name="email" required></label><br>
            <label>Відділ: <input type="text" name="department"></label><br>
            <label>Повідомлення:<br>
                <textarea name="message" rows="4" cols="40"></textarea>
            </label><br>
            <button type="submit">Send</button>
            </form>
            </div>   
        </div>   
    </div>
@endsection