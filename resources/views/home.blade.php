<x-layout>
    <x-slot name="title">
        Home page
    </x-slot>
    <h1>Home</h1>
    <p>Welcome to our home page!</p>
    <p>Here you can find out more about our company and our services.</p>
    <p>Feel free to contact us if you have any questions.</p>
    <p>Thank you for visiting our website!</p>
    @foreach($products as $product)
    <div>
        <h2>{{ $product->name }}</h2>
        <p>{{ $product->description }}</p>
    </div>
    @endforeach
</x-layout>