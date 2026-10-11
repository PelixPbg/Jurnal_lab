@extends('layouts.app')
@section('title', 'Check-Out Laboratorium')

@section('content')
<!-- Latar berwarna di belakang kartu kaca -->
<div class="pointer-events-none fixed inset-0 -z-10 bg-gradient-to-br from-amber-50 via-indigo-100 to-sky-200"></div>
<div class="pointer-events-none fixed -top-24 -left-24 w-96 h-96 bg-amber-300 rounded-full blur-3xl opacity-40 -z-10"></div>
<div class="pointer-events-none fixed top-1/3 -right-24 w-96 h-96 bg-blue-400 rounded-full blur-3xl opacity-40 -z-10"></div>
<div class="pointer-events-none fixed -bottom-24 left-1/4 w-96 h-96 bg-indigo-400 rounded-full blur-3xl opacity-30 -z-10"></div>

<div class="max-w-2xl mx-auto bg-white/50 backdrop-blur-xl p-8 sm:p-10 rounded-3xl shadow-2xl shadow-indigo-200/40 border border-white/60">
    @include('siswa._check_out_form')
</div>

<!-- Script kecil untuk menampilkan/menyembunyikan form masalah -->
<script>
    function toggleProblemBox(show) {
        const box = document.getElementById('problem_box');
        const input = document.getElementById('problem_description');
        if (show) {
            box.classList.remove('hidden');
            input.setAttribute('required', 'required');
        } else {
            box.classList.add('hidden');
            input.removeAttribute('required');
            input.value = '';
        }
    }
</script>
@endsection