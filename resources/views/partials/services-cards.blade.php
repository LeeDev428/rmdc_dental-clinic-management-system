@foreach($procedures as $procedure)
<div class="service-card rounded-xl shadow-lg bg-white dark:bg-gray-800 hover:scale-105 transform transition duration-300 ease-in-out">
    <img src="{{ asset('storage/' . $procedure->image_path) }}"
         alt="{{ $procedure->procedure_name }}"
         class="w-full h-44 object-cover rounded-t-xl">
    <div class="p-4">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $procedure->procedure_name }}</h3>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 line-clamp-3">
            {{ $procedure->description ?? 'No description available.' }}
        </p>
        <div class="mt-4 text-sm text-gray-600 dark:text-gray-300">
            <p><strong>Estimated Time:</strong> {{ $procedure->duration }} Minutes</p>
            <p><strong>Price:</strong> ₱{{ number_format($procedure->price, 2) }}</p>
        </div>
    </div>
</div>
@endforeach
