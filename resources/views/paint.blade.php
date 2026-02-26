<!DOCTYPE html>
<html lang="es">

@extends('layouts.app') @section('title')
{{ $tituloPagina ?? ($nombre ?? "Entrevista") }}
@endsection @section('content')
<nav
    class="fixed
     top-0 left-0 w-full h-16 md:h-20 flex items-center px-4 z-[9999] bg-[#34113F]/80 backdrop-blur pointer-events-auto">
    <a href="{{ route($volverRoute ?? 'entrevistas.index') }}" class="text-[#f8f8fa] font-bold text-lg">&larr; Espejito,
        espejito</a>
</nav>

<div class="h-[160px] md:h-[100px]"></div>

<head>
    <meta charset="UTF-8">
    <title>Dibujar sobre imagen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Inter, Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a
        }

        .bar {
            position: sticky;
            top: 0;
            display: flex;
            align-items: center;
            gap: .5rem;
            height: var(--bar-h);
            padding: 0 .75rem;
            background: transparent !important;
            border: none !important;
            backdrop-filter: saturate(180%) blur(8px);
            border-bottom: 1px solid #e2e8f0;
            z-index: 50
        }

        /* === Barra de herramientas === */
        .subbar {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: 0.2rem 0.5rem;
            min-height: 7px;
            background: transparent !important;
            border: none !important;
            position: relative;
            z-index: 1;
        }


        .subbar .left {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .subbar .center {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .subbar .right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: auto;
        }

        .field {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.9rem;
        }

        .stage-box {
            position: relative;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            overflow: hidden;
            max-width: min(90vw, 900px);
            margin: 0 auto;
        }

        #stage {
            position: relative;
            width: 100%;
            aspect-ratio: 2654 / 2127;
        }

        #frame {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            pointer-events: none;
            z-index: 15;
        }



        #bg {
            box-sizing: border-box;
            padding-left: 24px;
            padding-right: 24px;
            object-fit: contain;
            object-position: center;
        }


        #glass {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 5;
        }

        #cv {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
            pointer-events: auto;
            touch-action: none;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.55rem 1.1rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.95rem;
            white-space: nowrap;

            background: #E8E0F2;
            color: #3A245F;
            border: 1px solid #C8B7E2;

            box-shadow:
                0 3px 6px rgba(0, 0, 0, 0.12),
                inset 0 -2px rgba(255, 255, 255, 0.4);

            transition:
                transform 0.15s ease,
                background 0.2s ease,
                box-shadow 0.2s ease;
        }

        .btn:hover {
            background: #D9CFF0;
            transform: translateY(-1px);
        }

        .btn:active {
            background: #CFC3EB;
            transform: translateY(1px);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .btn {
            padding: .4rem .7rem;
            border: 1px solid #cbd5e1;
            border-radius: .6rem;
            background: #d9cce7;
            color: #34113F;
            font-size: .9rem;
            cursor: pointer
        }

        .btn[disabled] {
            opacity: .5;
            cursor: not-allowed
        }

        .btn:active,
        .chip:active {
            background: #34113F;
            color: #D9CCE7;
            transform: translateY(1px);
        }

        .btn.active,
        .chip.active {
            background: #D9CCE7;
        }

        .btn--upload {
            font-size: 1.05rem;
            padding: 0.7rem 1.4rem;
            background: #ffdce9;
            border-color: #d7a9bb;
            box-shadow: inset 0 -3px rgba(0, 0, 0, 0.07);
            animation: pulseUpload 2.2s ease-in-out 0s 20;
        }

        .btn--upload:hover {
            filter: brightness(1.05);
        }

        .btn--upload:active {
            background: #000;
            color: #fff;
            animation: none;
        }

        @keyframes pulseUpload {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 #D9CCE7;
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 8px 4px #BEB7DF;
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 #34113F;
            }
        }

        #file {
            display: none;
        }

        .chip {
            all: unset;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.42rem 0.75rem;
            border: 1px solid #34113F;
            border-radius: 9999px;
            background: #D9CCE7;
            color: #34113F;
            font-weight: 600;

            box-shadow: inset 0 -2px rgba(0, 0, 0, 0.06);
            transition: transform 0.05s ease, background 0.15s ease, color 0.15s ease;
        }

        .btn-primary {
            border-color: #d4f2d2;
            color: #065f46;
            background: #ecfdf5
        }

        .chip {
            padding: .4rem .7rem;
            border: 1px solid #D9CCE7;
            border-radius: 6rem;
            background: #fff;
            color: #34113F;
            font-size: .85rem;

            white-space: nowrap
        }

        .chip.active {
            background: #34113F;
            color: #D9CCE7;
            border-color: #34113F
        }

        .wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 12px
        }


        canvas {
            position: absolute;
            inset: 0;
            background: transparent;
            z-index: 10
        }

        .help {
            color: #64748b;
            font-size: .8rem;
            margin-top: .5rem
        }

        .spacer {
            height: .5rem
        }

        .field {
            display: flex;
            align-items: center;
            gap: .4rem
        }

        .field input[type="range"] {
            width: 160px
        }

        .q-fly {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow:
                0 12px 24px rgba(2, 6, 23, .06),
                0 2px 6px rgba(2, 6, 23, .03);
            padding: 12px;
            color: #0f172a;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transform: translateX(-100px) scale(.92);
            opacity: 0;
            filter: blur(4px);
            will-change: transform, opacity, filter;
        }

        .q-fly.hidden {
            display: none;
        }

        .q-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .q-body {
            line-height: 1.35;
            overflow: auto;
        }

        @keyframes flyIn {
            0% {
                transform: translateX(-100px) scale(.92);
                opacity: 0;
                filter: blur(4px);
            }

            100% {
                transform: translateX(0) scale(1);
                opacity: 1;
                filter: blur(0);
            }
        }

        @keyframes flyOut {
            0% {
                transform: translateX(0) scale(1);
                opacity: 1;
                filter: blur(0);
            }

            100% {
                transform: translateX(-80px) scale(.95);
                opacity: 0;
                filter: blur(4px);
            }
        }

        #placeholder {
            z-index: 30 !important;
        }


        .warn-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .warn-modal {
            background: #fff;
            border: 2px solid #dc2626;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            padding: 1.5rem;
            width: min(90vw, 420px);
            text-align: center;
        }

        .hidden {
            display: none !important;
        }


        .hidden {
            display: none !important;
        }

        .warn-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .55);
            backdrop-filter: blur(2px);
            display: grid;
            place-items: center;
            z-index: 9999;
        }

        .warn-modal {
            width: min(560px, 92vw);
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow:
                0 18px 36px rgba(2, 6, 23, .14),
                0 4px 12px rgba(2, 6, 23, .06);
            padding: 20px;
        }

        .q-fly {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 12px 24px rgba(2, 6, 23, .06), 0 2px 6px rgba(2, 6, 23, .03);
            padding: 12px;
            color: #0f172a;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }


        .btn {
            padding: .45rem .8rem;
            border: 1px solid #cbd5e1;
            border-radius: .6rem;
            background: #fff;
            color: #334155;
            font-size: .95rem;
            cursor: pointer;
        }

        .btn-primary {
            border-color: #34d399;
            color: #065f46;
            background: #ecfdf5;
        }

        .reflection-section {
            margin-top: 40px;
            background: #E5E3F7;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
            padding: 20px;
            max-width: 900px;
            margin-inline: auto;
        }

        .reflection-title {
            font-weight: 800;
            font-size: 1.25rem;
            color: #0f172a;
            margin-bottom: .5rem;
        }

        .reflection-desc {
            color: #334155;
            line-height: 1.5;
            margin-bottom: .75rem;
        }

        .reflection-textarea {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px;
            font: inherit;
            resize: vertical;
            min-height: 120px;
            color: #0f172a;
        }

        .reflection-actions {
            display: flex;
            justify-content: flex-end;
            gap: .75rem;
            margin-top: 10px;
        }

        .hidden {
            display: none !important;
        }

        .reflection-section {
            margin-top: 40px;
            background: #E5E3F7;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
            padding: 20px;
            max-width: 900px;
            margin-inline: auto;
        }

        .reflection-title {
            font-weight: 800;
            font-size: 1.25rem;
            color: #34113F;
            margin-bottom: .5rem;
        }

        .reflection-desc {
            color: #334155;
            line-height: 1.5;
            margin-bottom: .75rem;
        }

        .reflection-textarea {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px;
            font: inherit;
            resize: vertical;
            min-height: 120px;
            color: #0f172a;
        }

        .reflection-actions {
            display: flex;
            justify-content: flex-end;
            gap: .75rem;
            margin-top: 10px;
        }

        .tooltip-wrap {
            position: relative;
            display: inline-block;
        }

        .tooltip-text {
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(15, 23, 42, 0.95);
            color: #f8fafc;
            text-align: center;
            font-size: 0.85rem;
            line-height: 1.2;
            padding: 8px 10px;
            border-radius: 8px;
            width: max-content;
            max-width: 240px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .3);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease, transform 0.25s ease;
            white-space: normal;
            z-index: 999;
        }

        .tooltip-text::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: rgba(15, 23, 42, 0.95) transparent transparent transparent;
        }

        .tooltip-wrap:hover .tooltip-text {
            opacity: 1;
            transform: translate(-50%, -4px);
        }

        @media (hover: none) {
            .tooltip-text {
                display: none;
            }
        }

        .instructions-card {
            max-width: 1024px;
            margin: .4rem auto 1rem;
            border-radius: 12px;
            background: #D9CCE7;
            overflow: hidden;
        }

        .instructions-card>summary {
            list-style: none;
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .55rem .85rem;

            font-weight: 700;
            user-select: none;
        }

        .instructions-card>summary::-webkit-details-marker {
            display: none;
        }

        .instructions-card .marker {
            display: inline-flex;
            width: 1.35rem;
            height: 1.35rem;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            border: 1px solid #34113F;
            font-size: .9rem;
            line-height: 1;
            color: #34113F;
        }

        .instructions-card .hint {
            margin-left: auto;
            font-weight: 600;
            font-size: .85rem;
            color: #6b7280;
        }

        .instructions-content {
            padding: .75rem .95rem 1rem;
            font-size: .95rem;
            line-height: 1.45;
            color: #111;

        }

        .instructions-card .instructions-content {
            max-height: 600px;
            opacity: 1;
            transition: max-height .25s ease, opacity .25s ease;
        }

        .instructions-card:not([open]) .instructions-content {
            max-height: 0;
            opacity: 0;
            padding-top: 0;
            padding-bottom: 0;
            overflow: hidden;
        }

        .overlay {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, .55);
            backdrop-filter: blur(2px);
            z-index: 1000;
        }

        .overlay.hidden {
            display: none;
        }

        .modal {
            background: #fff;
            border-radius: 12px;
            padding: 1.4rem 1.8rem;
            max-width: 1000px;
            text-align: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: .6rem;
        }

        .modal-desc {
            color: #333;
            margin-bottom: 1rem;
        }

        #finalOverlay {
            position: fixed !important;
            inset: 0 !important;
            z-index: 7000 !important;
            background: rgba(0, 0, 0, .55) !important;
            backdrop-filter: blur(3px);
        }

        #finalOverlay .modal {
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            max-width: none !important;
            margin: 0 !important;
            border-radius: 0 !important;
            background: #fff !important;
            display: flex !important;
            flex-direction: column !important;
            box-shadow: none !important;
        }

        #finalOverlay .modal-header {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 12px 20px;
        }

        #finalOverlay .modal-body {
            position: relative;
            z-index: 1;
            margin: 0 auto;

            width: min(45vw, 640px);
            max-height: min(70vh, 680px);
            transform: translateY(20vh);

            overflow-y: auto;
            padding: 2.8rem 3.2rem;

            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);

            font-size: 1.05rem;
            line-height: 1.7;
            color: #111;

            backdrop-filter: blur(2px);
        }


        #finalOverlay .modal-body :is(.prose, .container, .mx-auto, [class*="max-w"], [class*="container"]) {
            max-width: 100% !important;
            width: 100% !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        #finalOverlay .modal-footer {
            position: sticky;
            bottom: 0;
            z-index: 2;
            backdrop-filter: blur(2px);
            padding: 10px 16px;
            display: flex;
            justify-content: flex-end;
            gap: .5rem;
        }

        #finalOverlay .btn {
            padding: .6rem 1.2rem;
            font-weight: 600;
            border: 1px solid #111;
            border-radius: 9999px;
            background: #fff;
            color: #111;
        }

        #finalOverlay .btn:active {
            background: #000;
            color: #fff;
        }

        body.modal-open {
            overflow: hidden !important;
        }


        #finalOverlay .modal {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(2px);
        }


        #finalOverlay .modal {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(2px);
        }

        #finalOverlay .modal {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            background-color: #fff;
            background-image: href="{{ secure_url('/img/#tocador2.png') }}";
            background-repeat: no-repeat;
            background-size: cover;

            background-position: center;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }


        #finalOverlay .modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 80px clamp(5vw, 8vw, 120px);

            color: #111;
            line-height: 1.7;
            font-size: 1.05rem;
            background: #F5F1FA;

            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }


        #finalOverlay .modal-header,
        #finalOverlay .modal-footer {
            background: transparent;
            border: none;
        }

        #finalOverlay .modal-footer {
            position: sticky;
            bottom: 0;
            padding: 1rem 2rem;
            display: flex;
            justify-content: flex-end;
        }

        #finalOverlay .btn {
            padding: .6rem 1.2rem;
            font-weight: 600;
            border: 1px solid #34113F;
            border-radius: 9999px;
            background: #D9CCE7;
            color: #111;
        }

        #finalOverlay .btn:active {
            background: #34113F;
            color: #D9CCE7;
        }


        #finalOverlay {
            position: fixed;
            inset: 0;
            background: #34113F;
            backdrop-filter: blur(3px);
            z-index: 10000;
            display: flex;
            align-items: stretch;
            justify-content: stretch;
        }

        #finalOverlay.hidden {
            display: none;
        }


        #finalOverlay .modal-full {
            position: fixed;
            inset: 0;
            display: grid;
            grid-template-rows: auto 1fr auto;
            width: 100vw;
            height: 100vh;
            background: transparent;
            overflow: hidden;
        }

        #finalOverlay .modal-header,
        #finalOverlay .modal-body,
        #finalOverlay .modal-footer {
            position: relative;
            z-index: 1;
            color: #34113F;
        }

        #finalOverlay .modal-header {
            padding: 1rem 2rem;
        }

        #finalOverlay .modal-title {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 800;
            color: #34113F;
        }

        #finalOverlay .modal-body {
            position: relative;
            z-index: 1;
            margin: 0 auto;
            width: 56vw;

            max-height: 65vh;

            overflow: auto;
            padding: 2rem 3rem;
            background: #E5E3F7;
            border-radius: 12px;
            box-shadow: 0 6px 18px #34113F;
            transform: translateY(19vh);
        }

        #finalOverlay .modal-footer {
            position: fixed;
            bottom: 0;
            right: 0;
            padding: 1rem 2rem;
            background: #E5E3F7;
            backdrop-filter: blur(4px);
            border-top-left-radius: 12px;
        }

        #finalOverlay .btn {
            padding: .6rem 1.2rem;
            font-weight: 600;
            border: 1px solid #BEB7DF;
            border-radius: 9999px;
            background: #D9CCE7;
            color: #34113F;
            transition: background .15s, color .15s;
        }

        #finalOverlay .btn:active {
            background: #34113F;
            color: #D9CCE7;
        }
    </style>
</head>

<body>
    <div class="bar">
        <strong style="margin:0 auto;" class="text-3xl font-bold mb-12">Tocador</strong>
    </div>

    <details id="inst" class="instructions-card" open>
        <summary>
            <span class="marker"></span>
            Instrucciones
            <span class="hint">(clic para mostrar / ocultar)</span>
        </summary>

        <div class="instructions-content" display: flex, justify-content: center>
            <p> <strong>Invirtamos los roles;</strong> este espejo te hará un par de preguntas que aparecen con solo
                ubicar el labial en el lienzo. </p>
            <p> Te advierto que <strong> no existe una respuesta
                    correcta;</strong> es más bien una invitación a reflexionar sobre tu alrededor y el entramado
                social. Recuerda que esta sección es para ti y tus propias reflexiones.La información que escribas aquí
                no será
                almacenada ni recolectada. </p> <br>
            <p> <strong>1)</strong> Te pediré que te tomes una selfie que nunca compartirías con alguien y cargarla
                usando el botón
                <strong>“Cargar selfie"</strong>.
            </p> <br>
            <p> <strong>2)</strong> Utiliza el labial para marcar tu selfie según las indicaciones de las preguntas en
                cada caso. Puedes hacer dibujos,
                símbolos o escribir alguna palabra o mensaje. No tienes que ser una artista; solo responde a cada
                pregunta indicando qué parte cambiarías. </p>
        </div>
    </details>
    <div class="subbar">

        <div class="left">
            <div class="field">
                <span>Color</span>
                <input id="color" type="color" value="#ff1f6d">
            </div>

            <div class="field">
                <span>Grosor</span>
                <input id="size" type="range" min="1" max="60" step="1" value="8">
                <span id="sizev">8</span>
            </div>

            <button id="undo" class="btn">↶ Deshacer</button>
            <button id="redo" class="btn">↷ Rehacer</button>
            <label for="file" class="btn btn--upload" style="cursor:pointer">
                <input id="file" type="file" accept="image/*">
                Cargar selfie
            </label>
        </div>

        <div class="right">
            <button id="modeDraw" class="btn chip active">Rayar</button>
            <button id="modeErase" class="btn chip">Borrar</button>
            <button id="clear" class="btn chip">Limpiar Todo</button>
        </div>

    </div>





    <div class="stage-box" style="display:flex; align-items:stretch; gap:20px;">
        <aside style="flex:0 0 300px;">
            <div id="qFly" class="q-fly hidden">
                <div class=" q-head text-xl" style="color:#34113F;">
                    <strong>Pregunta</strong>
                </div>

                <div id="qText" class="q-body" style="font-weight:500;">
                    ¿Cuál es tu color favorito?
                </div>

                <label style="font-size:.8rem;color:#64748b;">Tu respuesta</label>
                <textarea id="qInput" rows="4"
                    style="resize:vertical;width:100%;border:1px solid #e2e8f0;border-radius:10px;padding:8px;outline:none;font:inherit;"
                    placeholder="Escribe aquí..."></textarea>

                <div class="q-foot" style="margin-top:8px;display:flex;gap:8px;justify-content:space-between;">
                    <button hidden id="qDownload" class="btn">⬇️ Descargar</button>
                    <button id="qNext" class="btn btn-primary">Continuar →</button>

                </div>
            </div>


        </aside>

        <div id="warnOverlay" aria-modal="true" role="dialog" style="
       position: fixed;
       inset: 0;
       z-index: 9990;
       display: flex;
       align-items: center;
       justify-content: center;
       /* FONDO LAVANDA COMO HITOS (D9CCE7) */
       background: rgba(217, 204, 231, 0.82); /* #D9CCE7 con transparencia */
       backdrop-filter: blur(10px);
     ">
            <div class="warn-modal" role="document" style="
      width: min(92vw, 720px);
      max-width: 720px;
      /* TARJETA CLARITA CON BORDE LILA */
      background-color: #ffffff;
      border: 2px solid #BEB7DF;
      border-radius: 32px;
      padding: 3rem 3.5rem;
      text-align: center;
      box-shadow: 0 18px 45px rgba(171, 169, 191, 0.55); /* #E5E3F7 base */
    ">
                <h2 style="
        color:#34113F;
        font-weight:900;
        font-size:2.2rem;
        text-transform:uppercase;
        letter-spacing:1px;
        margin-bottom:1.8rem;
      ">
                    ADVERTENCIA DE CONTENIDO
                </h2>

                <p style="
        line-height:1.6;
        font-size:1.2rem;
        color:#34113F;
        margin-bottom:2rem;
      ">
                    Este ejercicio contiene preguntas sobre modificación facial, cirugía plástica e inseguridades.
                    <br><br>
                    Si no te sientes en la condición de hablar sobre estos temas, te recomiendo que saltes esta sección.
                    <br><br>
                    Presione <strong>Tocador</strong> para ver las preguntas o
                    <strong>Salón de espejos</strong> para regresar.
                </p>

                <div style="display:flex; justify-content:center; gap:1.2rem;">
                    <button id="btnWarnBack" class="btn" style="
          flex:1;
          background:#D9CCE7;
          border:2px solid #BEB7DF;
          color:#34113F;
          font-weight:700;
          padding:1rem 1.3rem;
          border-radius:12px;
          font-size:1.05rem;
          cursor:pointer;
          transition:.15s;
        ">
                        ← Salón de espejos
                    </button>
                    <button id="btnWarnContinue" class="btn btn-primary" style="
          flex:1;
          background:#34113F;
          border:2px solid #34113F;
          color:#D9CCE7;
          font-weight:700;
          padding:1rem 1.3rem;
          border-radius:12px;
          font-size:1.05rem;
          cursor:pointer;
          transition:.15s;
        ">
                        Tocador →
                    </button>
                </div>
            </div>
        </div>



        <div id="finalOverlay" class="overlay hidden">
            <div class="modal-full">
                <img src="{{ asset('img/tocador2.png') }}?v=3" alt="" class="modal-frame">
                <section class="modal-body">
                    <div class="fc-reset">
                        <p class="modal-desc" text-align:center;> Gracias por verte en este espejo,</p>
                        <p class="modal-desc">Aunque la belleza no sea lo más importante para ti, está en todas partes:
                            en tu elección de ropa para la entrevista de trabajo, en arreglarse para una cita romántica
                            o para verse con las amigas. Piensa en las historias de las mujeres que leíste y en los
                            hilos que la conforman. Los factores son múltiples y se enredan entre sí.</p>
                        <p class="modal-desc">El proyecto quiere informarte, no juzgarte. Espero que la información de
                            esta página te sirva para redefinir lo que tú quieres con la belleza y negociar tu proceso
                            de embellecimiento en los momentos que consideres. Tomes la decisión que tomes, serás
                            juzgada: es parte de la paradoja de la belleza. Pierdes si quieres ser bella, pierdes si no
                            lo quieres. Sin embargo, quiero dar un paso más allá para romper el blanco y negro y ver el
                            enredo en el que tienes que vivir. </p>
                        <p class="modal-desc">Espero que encuentres aquí un lugar para pensar la belleza desde una
                            resistencia colectiva. Existirán muchas presiones a lo largo de tu vida y probablemente más
                            con las innovaciones tecnológicas. La belleza es una moneda de cambio tanto por sus
                            beneficios como desventajas. Así que, al igual que tú decides cómo negociar con ella, si
                            aspiras a pasar inadvertida, a la diferenciación o la notoriedad; abraza la incoherencia que
                            viene con ello, mírate al espejo y pregúntale: “Espejito, espejito, ¿de dónde viene este
                            enredo?”</p>
                        <p class="modal-desc">Tómate un momento, cuando quieras, continúa. </p>
                    </div>
                </section>

                <footer class="modal-footer">
                    <button id="btnContinuarFinal" class="btn">Continuar </button>
                </footer>
            </div>
        </div>

        <div
        id="stage"
        class="stage"
        style="flex: 1 1 auto; min-width: 0; position: relative; overflow: hidden"
        >
        <img
            id="bg"
            alt=""
            style="
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            z-index: 5;
            user-select: none;
            -webkit-user-drag: none;
            "
        />
        <div
            id="glass"
            class="glass"
            style="
            position: absolute;
            top: 14.5%;
            left: 9%;
            right: 10%;
            bottom: 14%;
            overflow: hidden;
            "
        >
            <canvas
            id="cv"
            style="
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                z-index: 10;
                pointer-events: auto;
                touch-action: none;
                display: block;
            "
            ></canvas>
            <div id="placeholder" class="glass-placeholder">
            <div style="text-align: left; width: 100%; max-width: 300px">
                <div style="font-size: 1.1rem; margin-bottom: 0.25rem">
                Cargar una imagen para empezar
                </div>
                <div>o dibuja sobre un lienzo en blanco</div>
            </div>
            </div>
        </div>
        <img id="frame" src="tocador.png" alt="Marco" />
        </div>
        <script>
            (function () {
                document.addEventListener('DOMContentLoaded', () => {
                    const warnOverlay = document.getElementById('warnOverlay');
                    const btnWarnBack = document.getElementById('btnWarnBack');
                    const btnWarnContinue = document.getElementById('btnWarnContinue');
                    const warnDontShowEl = document.getElementById('warnDontShow');
                    const qFlyPanel = document.getElementById('qFly'); // tu panel de preguntas
                    if (!warnOverlay) return;

                    try {
                        const ok = localStorage.getItem('mirrorConsent');
                        if (ok === 'yes') warnOverlay.classList.add('hidden');
                    } catch (_) {
                    }

                    if (btnWarnBack) {
                        btnWarnBack.addEventListener('click', () => {
                            window.location.href = "{{ route('entrevistas.index') }}";
                        });
                    }

                    if (btnWarnContinue) {
                        btnWarnContinue.addEventListener('click', () => {
                            if (warnDontShowEl?.checked) {
                                try { localStorage.setItem('mirrorConsent', 'yes'); } catch { }
                            }
                            warnOverlay.classList.add('hidden');
                            qFlyPanel?.classList.remove('hidden');
                        });
                    }
                });
                const file = document.getElementById('file');
                const color = document.getElementById('color');
                const size = document.getElementById('size');
                const sizev = document.getElementById('sizev');
                const modeDraw = document.getElementById('modeDraw');
                const modeErase = document.getElementById('modeErase');
                const stage = document.getElementById('stage');
                const bg = document.getElementById('bg');
                const cv = document.getElementById('cv');
                const ph = document.getElementById('placeholder');
                const undoBtn = document.getElementById('undo');
                const redoBtn = document.getElementById('redo');
                const clearBtn = document.getElementById('clear');
                const ctx = cv.getContext('2d', { willReadFrequently: true });
                const frame = document.getElementById('frame');

                let brushColor = color.value;
                let brushSize = parseInt(size.value, 10);
                let mode = 'draw';
                let brush = 'lipstick';
                let bgLoaded = false;
                let drawing = false, lastX = 0, lastY = 0, lastStampX = 0, lastStampY = 0, stampSpacing = 0.35;
                let history = [], redoStack = [], historyLimit = 50;


                function updateCursor() {
                    let path = '';
                    if (mode === 'erase') path = "{{ asset('img/cursors/borrar.png') }}";
                    else {
                        switch (brush) {
                            case 'lipstick': path = "{{ asset('img/cursors/labial.png') }}"; break;
                            case 'shadow': path = "{{ asset('img/cursors/sombra.png') }}"; break;
                            case 'blush': path = "{{ asset('img/cursors/rubor.png') }}"; break;
                            case 'eyeliner': path = "{{ asset('img/cursors/delineador.png') }}"; break;
                            case 'remover': path = "{{ asset('img/cursors/desmaquillante.png') }}"; break;
                            default: path = '';
                        }
                    }
                    if (path) cv.style.cursor = `url("${path}") 16 16, crosshair`;
                    else cv.style.cursor = 'crosshair';
                }

                function withAlpha(hex, a = 1) {
                    if (!hex) return `rgba(0,0,0,${a})`;
                    if (hex.startsWith('#')) {
                        const c = hex.length === 4 ? hex.replace('#', '').split('').map(ch => ch + ch).join('') : hex.replace('#', '');
                        const r = parseInt(c.slice(0, 2), 16), g = parseInt(c.slice(2, 4), 16), b = parseInt(c.slice(4, 6), 16);
                        return `rgba(${r},${g},${b},${a})`;
                    }
                    return hex;
                }
                function clamp(v, min, max) { return Math.max(min, Math.min(max, v)); } function resize() {
                    const dpr = window.devicePixelRatio || 1;

                    const glass = document.getElementById('glass');
                    if (!glass) return;

                    const hasSelfie = bg.naturalWidth > 0 && bg.naturalHeight > 0;

                    const baseW = hasSelfie
                        ? bg.naturalWidth
                        : (frame?.naturalWidth || 723);

                    const baseH = hasSelfie
                        ? bg.naturalHeight
                        : (frame?.naturalHeight || 723);

                    const aspect = baseW / baseH;

                    const headerH =
                        (document.querySelector('.bar')?.getBoundingClientRect().height || 0) +
                        (document.querySelector('.subbar')?.getBoundingClientRect().height || 0);

                    const maxW_vp = Math.floor(window.innerWidth * 0.9);
                    const maxH_vp = Math.floor((window.innerHeight - headerH) * 0.8);

                    const boxRect = stage.parentElement.getBoundingClientRect();
                    const maxW = Math.max(240, Math.min(maxW_vp, boxRect.width));
                    const maxH = Math.max(240, Math.min(maxH_vp, 2000));
                    let dispW = maxW;
                    let dispH = Math.round(dispW / aspect);

                    if (dispH > maxH) {
                        dispH = maxH;
                        dispW = Math.round(dispH * aspect);
                    }

                    stage.style.width = dispW + 'px';
                    stage.style.height = dispH + 'px';

                    bg.style.position = 'absolute';
                    bg.style.inset = '0';
                    bg.style.width = '100%';
                    bg.style.height = '100%';
                    bg.style.objectFit = 'contain';
                    bg.style.zIndex = '1';

                    frame.style.position = 'absolute';
                    frame.style.inset = '0';
                    frame.style.width = '100%';
                    frame.style.height = '100%';
                    frame.style.objectFit = 'contain';
                    frame.style.zIndex = '15';
                    frame.style.pointerEvents = 'none';

                    const FRAME_W = 723;
                    const FRAME_H = 723;

                    const M_TOP = 98;
                    const M_BOTTOM = 104;
                    const M_LEFT = 86;
                    const M_RIGHT = 86;

                    const sX = dispW / FRAME_W;
                    const sY = dispH / FRAME_H;

                    const glassX = M_LEFT * sX;
                    const glassY = M_TOP * sY;
                    const glassW = (FRAME_W - M_LEFT - M_RIGHT) * sX;
                    const glassH = (FRAME_H - M_TOP - M_BOTTOM) * sY;

                    glass.style.position = "absolute";
                    glass.style.left = glassX + "px";
                    glass.style.top = glassY + "px";
                    glass.style.width = glassW + "px";
                    glass.style.height = glassH + "px";

                    cv.style.left = "0px";
                    cv.style.top = "0px";
                    cv.style.width = "100%";
                    cv.style.height = "100%";

                    cv.width = Math.round(glassW * dpr);
                    cv.height = Math.round(glassH * dpr);
                    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                }

                function pushHistory() {
                    try {
                        const snap = ctx.getImageData(0, 0, cv.width, cv.height);
                        history.push(snap); if (history.length > historyLimit) history.shift();
                        undoBtn.disabled = history.length === 0;
                        redoBtn.disabled = redoStack.length === 0;
                    } catch (e) {
                        console.warn('Historial deshabilitado (posible CORS).', e);
                    }
                }
                function undo() {
                    if (!history.length) return;
                    const curr = ctx.getImageData(0, 0, cv.width, cv.height);
                    const prev = history.pop(); if (!prev) return;
                    redoStack.push(curr); ctx.putImageData(prev, 0, 0);
                    undoBtn.disabled = history.length === 0;
                    redoBtn.disabled = redoStack.length === 0;
                }
                function redo() {
                    if (!redoStack.length) return;
                    const curr = ctx.getImageData(0, 0, cv.width, cv.height);
                    const next = redoStack.pop(); if (!next) return;
                    history.push(curr); ctx.putImageData(next, 0, 0);
                    undoBtn.disabled = history.length === 0;
                    redoBtn.disabled = redoStack.length === 0;
                }

                function eventXY(e) {
                    const rect = cv.getBoundingClientRect();
                    const t = e.touches?.[0];
                    const cx = t ? t.clientX : e.clientX;
                    const cy = t ? t.clientY : e.clientY;
                    const x = (cx - rect.left) * (cv.width / rect.width);
                    const y = (cy - rect.top) * (cv.height / rect.height);
                    return { x, y };
                }

                function pointerDown(e) {
                    ctx.shadowBlur = 0; ctx.shadowColor = 'transparent';
                    const { x, y } = eventXY(e);
                    drawing = true; lastX = x; lastY = y; lastStampX = x; lastStampY = y;

                    if (mode === 'erase') {
                        ctx.globalCompositeOperation = 'destination-out';
                        ctx.lineCap = 'round'; ctx.lineJoin = 'round';
                        ctx.lineWidth = brushSize; ctx.strokeStyle = 'rgba(0,0,0,1)';
                        ctx.beginPath(); ctx.moveTo(x, y); return;
                    }
                    ctx.globalCompositeOperation = 'source-over';

                    if (brush === 'eyeliner') {
                        ctx.lineCap = 'butt'; ctx.lineJoin = 'round';
                        ctx.lineWidth = Math.max(1, brushSize * 0.55);
                        ctx.strokeStyle = brushColor;
                        ctx.beginPath(); ctx.moveTo(x, y);
                    } else if (brush === 'lipstick') {
                        ctx.lineCap = 'round'; ctx.lineJoin = 'round';
                        ctx.lineWidth = Math.max(2, brushSize * 0.9);
                        ctx.strokeStyle = withAlpha(brushColor, 0.85);
                        ctx.shadowColor = brushColor; ctx.shadowBlur = Math.floor(brushSize * 0.15);
                        ctx.beginPath(); ctx.moveTo(x, y);
                    }
                }

                function pointerMove(e) {
                    if (!drawing) return;
                    const { x, y } = eventXY(e);

                    if (mode === 'erase') {
                        ctx.lineTo(x, y); ctx.stroke(); lastX = x; lastY = y; return;
                    }

                    if (brush === 'eyeliner' || brush === 'lipstick') {
                        ctx.lineTo(x, y); ctx.stroke(); lastX = x; lastY = y; return;
                    }
                    const dist = Math.hypot(x - lastStampX, y - lastStampY);
                    const step = brushSize * stampSpacing;
                    if (dist >= step) {
                        const n = Math.floor(dist / step);
                        for (let i = 1; i <= n; i++) {
                            const t = i / n, sx = lastStampX + (x - lastStampX) * t, sy = lastStampY + (y - lastStampY) * t;
                            stamp(sx, sy);
                        }
                        lastStampX = x; lastStampY = y;
                    }
                }

                function pointerUp() {
                    if (!drawing) return;
                    drawing = false; try { ctx.closePath(); } catch (_) { }
                    ctx.shadowBlur = 0;
                    pushHistory(); redoStack = [];
                }

                function stamp(x, y) {
                    if (brush === 'shadow') {
                        const r = brushSize * 0.6;
                        const g = ctx.createRadialGradient(x, y, 0, x, y, r);
                        g.addColorStop(0, withAlpha(brushColor, 0.18));
                        g.addColorStop(1, withAlpha(brushColor, 0.00));
                        const prev = ctx.globalCompositeOperation;
                        ctx.globalCompositeOperation = 'lighter';
                        ctx.fillStyle = g; ctx.beginPath(); ctx.arc(x, y, r, 0, Math.PI * 2); ctx.fill();
                        ctx.globalCompositeOperation = prev; return;
                    }
                    if (brush === 'blush') {
                        const R = brushSize * 0.96;
                        const g = ctx.createRadialGradient(x, y, 0, x, y, R);
                        g.addColorStop(0, withAlpha(brushColor, 0.12));
                        g.addColorStop(1, withAlpha(brushColor, 0.00));
                        const prev = ctx.globalCompositeOperation;
                        ctx.globalCompositeOperation = 'lighter';
                        ctx.fillStyle = g; ctx.beginPath(); ctx.arc(x, y, R, 0, Math.PI * 2); ctx.fill();
                        ctx.globalCompositeOperation = prev; return;
                    }
                    if (brush === 'remover') {
                        const r = brushSize * 0.7;
                        const g = ctx.createRadialGradient(x, y, 0, x, y, r);
                        g.addColorStop(0, 'rgba(255,255,255,0.25)');
                        g.addColorStop(0.3, 'rgba(255,255,255,0.10)');
                        g.addColorStop(1, 'rgba(255,255,255,0.00)');
                        const prev = ctx.globalCompositeOperation;
                        ctx.globalCompositeOperation = 'destination-out';
                        ctx.fillStyle = g; ctx.beginPath(); ctx.arc(x, y, r, 0, Math.PI * 2); ctx.fill();
                        ctx.globalCompositeOperation = prev; return;
                    }
                }

                file.addEventListener('change', (e) => {
                    const f = e.target.files?.[0]; if (!f) return;
                    const reader = new FileReader();
                    reader.onload = () => { bg.src = reader.result; };
                    reader.readAsDataURL(f);
                });



                bg.addEventListener('load', () => {
                    bgLoaded = true; ph.style.display = 'none'; resize(); pushHistory(); redoStack = [];
                });

                size.addEventListener('input', () => { brushSize = parseInt(size.value, 10); sizev.textContent = size.value; });
                color.addEventListener('input', () => { brushColor = color.value; });

                modeDraw.addEventListener('click', () => {
                    mode = 'draw'; modeDraw.classList.add('active'); modeErase.classList.remove('active'); updateCursor();
                });
                modeErase.addEventListener('click', () => {
                    mode = 'erase'; modeErase.classList.add('active'); modeDraw.classList.remove('active'); updateCursor();
                });

                document.querySelectorAll('[data-brush]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        document.querySelectorAll('[data-brush]').forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                        brush = btn.getAttribute('data-brush');
                        updateCursor();
                    });
                });

                undoBtn.addEventListener('click', undo);
                redoBtn.addEventListener('click', redo);
                clearBtn.addEventListener('click', () => {
                    if (!confirm('¿Limpiar todos los trazos?')) return;
                    ctx.save(); ctx.setTransform(1, 0, 0, 1, 0, 0); ctx.clearRect(0, 0, cv.width, cv.height); ctx.restore();
                    pushHistory(); redoStack = [];
                });
                cv.addEventListener('pointerdown', (e) => {
                    cv.setPointerCapture(e.pointerId);
                    pointerDown(e);
                });
                cv.addEventListener('pointermove', pointerMove);
                cv.addEventListener('pointerup', pointerUp);
                cv.addEventListener('pointercancel', pointerUp);

                window.addEventListener('keydown', (e) => {
                    const k = e.key.toLowerCase();
                    if ((e.ctrlKey || e.metaKey) && k === 'z') { e.preventDefault(); return undo(); }
                    if ((e.ctrlKey || e.metaKey) && k === 'y') { e.preventDefault(); return redo(); }
                    if (k === 'e') { mode = 'erase'; modeErase.classList.add('active'); modeDraw.classList.remove('active'); updateCursor(); }
                    if (k === 'd') { mode = 'draw'; modeDraw.classList.add('active'); modeErase.classList.remove('active'); updateCursor(); }
                });

                window.addEventListener('resize', () => { resize(); });

                resize(); updateCursor(); pushHistory();

                const Q = [
                    "Si pudieras modificar cualquier cosa de tu rostro sin dolor o secuelas, ¿qué cambiarías? Señala en la imagen o escribe. CONTINUAR",
                    "Ahora puedes cambiar cosas, pero tienes que hacerte un procedimiento radical. ¿Qué cosas intervendrías con cirugías? Señala en la imagen o escribe. CONTINUAR",
                    "Solo puedes utilizar tratamientos no invasivos para cambiar tu rostro: desde plantas, minerales, productos y tratamientos. ¿Qué cosas cambiarías con eso? Señala tu imagen o escribe. CONTINUAR",
                    "¿Alguna vez has recurrido a estos procesos radicales o no invasivos? Si es así, ¿por qué? Si no es el caso, ¿por qué no? Raya tu selfie o escribe. CONTINUAR",
                    "¿Qué cosas intervienen en tus decisiones y bajo qué condiciones? Si tu entorno fuera diferente, ¿cambiarias tu razonamiento? Raya tu selfie o escribe. CONTINUAR"
                ];

                let qIdx = 0;
                const qInput = document.getElementById('qInput');
                const qDownload = document.getElementById('qDownload');

                const answers = new Array(Q.length).fill('');

                const qFly = document.getElementById('qFly');
                const qText = document.getElementById('qText');
                const qNext = document.getElementById('qNext');

                const SPRING_IN = 'cubic-bezier(.22,1.25,.23,1)';
                const EASE_OUT = 'cubic-bezier(.3,.7,.2,1)';

                let qVisible = false;
                let overStage = false, overCard = false;
                let hideTimer = null;
                let isAnimating = false;

                function cancelAllAnims() {
                    qFly.getAnimations().forEach(a => a.cancel());
                }

                function showQ() {
                    if (qVisible && !qFly.classList.contains('hidden')) return;
                    cancelAllAnims();
                    qFly.classList.remove('hidden');
                    qVisible = true;
                    isAnimating = true;
                    qFly.animate(
                        [
                            { transform: 'translateX(-100px) scale(.92)', opacity: 0, filter: 'blur(4px)' },
                            { transform: 'translateX(0) scale(1)', opacity: 1, filter: 'blur(0)' }
                        ],
                        { duration: 480, easing: SPRING_IN, fill: 'both' }
                    ).onfinish = () => { isAnimating = false; };
                }

                function hideQ() {
                    if (!qVisible || qFly.classList.contains('hidden')) return;
                    cancelAllAnims();
                    isAnimating = true;
                    qFly.animate(
                        [
                            { transform: 'translateX(0) scale(1)', opacity: 1, filter: 'blur(0)' },
                            { transform: 'translateX(-80px) scale(.95)', opacity: 0, filter: 'blur(4px)' }
                        ],
                        { duration: 240, easing: EASE_OUT, fill: 'forwards' }
                    ).onfinish = () => { qFly.classList.add('hidden'); qVisible = false; isAnimating = false; };
                }

                function scheduleHide() {
                    clearTimeout(hideTimer);
                    hideTimer = setTimeout(() => {
                        if (!overStage && !overCard && !isAnimating) hideQ();
                    }, 130);
                }

                stage.addEventListener('mouseenter', () => { overStage = true; if (!isAnimating) showQ(); });
                stage.addEventListener('mouseleave', (e) => {
                    overStage = false;
                    if (qFly.contains(e.relatedTarget)) return;
                    scheduleHide();
                });

                qFly.addEventListener('mouseenter', () => { overCard = true; });
                qFly.addEventListener('mouseleave', (e) => {
                    overCard = false;
                    if (stage.contains(e.relatedTarget)) return;
                    scheduleHide();
                });

                function downloadCurrentQA() {

                    const question = Q[qIdx] || '';
                    const answer = (qInput.value || '').trim();

                    answers[qIdx] = answer;

                    const CSS_W = 680;
                    const PAD = 24;
                    const DPR = Math.max(1, Math.round(window.devicePixelRatio || 1));
                    const m = document.createElement('canvas').getContext('2d');
                    m.font = '600 18px system-ui, -apple-system, Segoe UI, Roboto, Inter, Arial, sans-serif';
                    const qLines = wrapText(m, question, CSS_W - PAD * 2);
                    m.font = '400 16px system-ui, -apple-system, Segoe UI, Roboto, Inter, Arial, sans-serif';
                    const aLines = (answer ? wrapText(m, answer, CSS_W - PAD * 2) : ['(sin respuesta)']);

                    const LH_Q = 24;
                    const LH_A = 22;
                    const TITLE_H = 14;
                    const GUTTER1 = 10;
                    const GUTTER2 = 10;
                    const LABEL_H = 14;
                    const GUTTER3 = 8;

                    const CSS_H = PAD + TITLE_H + GUTTER1 +
                        qLines.length * LH_Q +
                        GUTTER2 + LABEL_H + GUTTER3 +
                        aLines.length * LH_A +
                        PAD;

                    const c = document.createElement('canvas');
                    c.width = Math.round(CSS_W * DPR);
                    c.height = Math.round(CSS_H * DPR);
                    const ctx = c.getContext('2d');
                    ctx.scale(DPR, DPR);

                    roundRect(ctx, 0, 0, CSS_W, CSS_H, 16);
                    ctx.save();
                    ctx.shadowColor = 'rgba(2,6,23,0.06)';
                    ctx.shadowBlur = 18;
                    ctx.shadowOffsetY = 6;
                    ctx.fillStyle = '#34113F';
                    ctx.restore();
                    ctx.strokeStyle = '#e2e8f0';
                    ctx.lineWidth = 1;
                    ctx.stroke();

                    ctx.fillStyle = '#0f172a';
                    ctx.font = '700 14px system-ui, -apple-system, Segoe UI, Roboto, Inter, Arial, sans-serif';
                    ctx.fillText('', PAD, PAD + TITLE_H);

                    ctx.font = '600 18px system-ui, -apple-system, Segoe UI, Roboto, Inter, Arial, sans-serif';
                    let y = PAD + TITLE_H + GUTTER1;
                    qLines.forEach(line => { ctx.fillText(line, PAD, y); y += LH_Q; });

                    y += 2;
                    ctx.fillStyle = '#64748b';
                    ctx.font = '500 13px system-ui, -apple-system, Segoe UI, Roboto, Inter, Arial, sans-serif';
                    ctx.fillText('Tu respuesta', PAD, y);
                    y += LABEL_H + GUTTER3;

                    ctx.fillStyle = '#0f172a';
                    ctx.font = '400 16px system-ui, -apple-system, Segoe UI, Roboto, Inter, Arial, sans-serif';
                    aLines.forEach(line => { ctx.fillText(line, PAD, y); y += LH_A; });

                    const idxStr = String(qIdx + 1).padStart(2, '0');
                    const short = shortSlug(question, 18);
                    const aTag = document.createElement('a');
                    aTag.download = `Q${idxStr}-${short || 'pregunta'}.png`;
                    aTag.href = c.toDataURL('image/png');
                    aTag.click();

                    function roundRect(ctx, x, y, w, h, r) {
                        ctx.beginPath();
                        ctx.moveTo(x + r, y);
                        ctx.arcTo(x + w, y, x + w, y + h, r);
                        ctx.arcTo(x + w, y + h, x, y + h, r);
                        ctx.arcTo(x, y + h, x, y, r);
                        ctx.arcTo(x, y, x + w, y, r);
                        ctx.closePath();
                    }
                    function wrapText(ctx, text, maxWidth) {
                        const words = String(text || '').split(/\s+/);
                        const lines = [];
                        let line = '';
                        for (let i = 0; i < words.length; i++) {
                            const test = (line ? line + ' ' : '') + words[i];
                            if (ctx.measureText(test).width > maxWidth && line) {
                                lines.push(line);
                                line = words[i];
                            } else {
                                line = test;
                            }
                        }
                        if (line) lines.push(line);
                        return lines;
                    }
                    function shortSlug(s, max = 18) {
                        const base = String(s || '').toLowerCase()
                            .replace(/[^\p{L}\p{N}\s]/gu, '')
                            .trim()
                            .replace(/\s+/g, '-');
                        return base.slice(0, max).replace(/-+$/, '');
                    }
                }

                qDownload.addEventListener('click', (e) => {
                    e.stopPropagation();
                    downloadCurrentQA();
                });
                function resize() {
                    const dpr = window.devicePixelRatio || 1;

                    const glass = document.getElementById('glass');
                    if (!glass) return;

                    const hasSelfie = bg.naturalWidth > 0 && bg.naturalHeight > 0;

                    const baseW = hasSelfie
                        ? bg.naturalWidth
                        : (frame?.naturalWidth || 2654);

                    const baseH = hasSelfie
                        ? bg.naturalHeight
                        : (frame?.naturalHeight || 2127);

                    const aspect = baseW / baseH;

                    const headerH =
                        (document.querySelector('.bar')?.getBoundingClientRect().height || 0) +
                        (document.querySelector('.subbar')?.getBoundingClientRect().height || 0);

                    const maxW_vp = Math.floor(window.innerWidth * 0.9);
                    const maxH_vp = Math.floor((window.innerHeight - headerH) * 0.8);

                    const boxRect = stage.parentElement.getBoundingClientRect();
                    const maxW = Math.max(320, Math.min(maxW_vp, boxRect.width));
                    const maxH = Math.max(240, Math.min(maxH_vp, 2000));

                    let dispW = maxW;
                    let dispH = Math.round(dispW / aspect);

                    if (dispH > maxH) {
                        dispH = maxH;
                        dispW = Math.round(dispH * aspect);
                    }

                    stage.style.width = dispW + 'px';
                    stage.style.height = dispH + 'px';

                    const FRAME_W = 1100;
                    const FRAME_H = 410;

                    const M_TOP = 101;
                    const M_BOTTOM = 100;
                    const M_LEFT = 76;
                    const M_RIGHT = 0;

                    const sX = dispW / FRAME_W;
                    const sY = dispH / FRAME_H;

                    const glassX = M_LEFT * sX;
                    const glassY = M_TOP * sY;
                    const glassW = (FRAME_W - M_LEFT - M_RIGHT) * sX;
                    const glassH = (FRAME_H - M_TOP - M_BOTTOM) * sY;

                    glass.style.position = "absolute";
                    glass.style.left = glassX + "px";
                    glass.style.top = glassY + "px";
                    glass.style.width = glassW + "px";
                    glass.style.height = glassH + "px";


                    bg.style.position = 'absolute';
                    bg.style.left = glassX + 'px';
                    bg.style.top = glassY + 'px';
                    bg.style.width = glassW + 'px';
                    bg.style.height = glassH + 'px';
                    bg.style.objectFit = 'contain';
                    bg.style.objectPosition = 'center center';
                    bg.style.zIndex = '1';
                    bg.style.boxSizing = 'border-box';
                    bg.style.padding = '0 24px';

                    frame.style.position = 'absolute';
                    frame.style.inset = '0';
                    frame.style.width = '100%';
                    frame.style.height = '100%';
                    frame.style.objectFit = 'contain';
                    frame.style.zIndex = '15';
                    frame.style.pointerEvents = 'none';

                    cv.style.left = "0px";
                    cv.style.top = "0px";
                    cv.style.width = "100%";
                    cv.style.height = "100%";

                    const gRect = glass.getBoundingClientRect();

                    cv.width = Math.round(gRect.width * dpr);
                    cv.height = Math.round(gRect.height * dpr);
                    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

                }

                function syncModalFrameToStage() {
                    const stage = document.getElementById('stage');
                    const modalFrame = document.querySelector('#finalOverlay .modal-frame');
                    if (!stage || !modalFrame) return;

                    const r = stage.getBoundingClientRect();
                    const vw = window.innerWidth;
                    const vh = window.innerHeight;
                    const scale = Math.min((vw * 0.9) / r.width, (vh * 0.9) / r.height);

                    const w = r.width * scale;
                    const h = r.height * scale;

                    modalFrame.style.width = w + 'px';
                    modalFrame.style.height = h + 'px';
                }
                function abrirReflexionFinal() {
                    const overlay = document.getElementById('finalOverlay');
                    if (!overlay) return;

                    overlay.classList.remove('hidden');
                    document.body.classList.add('modal-open');

                    syncModalFrameToStage();
                }

                window.addEventListener('resize', syncModalFrameToStage);


                function nextQuestion() {
                    clearTimeout(hideTimer);
                    overCard = true;
                    if (!qVisible) showQ();
                    if (isAnimating) return;

                    isAnimating = true;
                    qNext.disabled = true;

                    qFly.animate(
                        [
                            { transform: 'translateX(0) scale(1)', opacity: 1, filter: 'blur(0)' },
                            { transform: 'translateX(-80px) scale(.95)', opacity: 0, filter: 'blur(4px)' }
                        ],
                        { duration: 240, easing: EASE_OUT, fill: 'forwards' }
                    ).onfinish = () => {
                        if (isLast()) {
                            showFinalModal();
                            isAnimating = false;
                            qNext.disabled = false;
                            return;
                        }

                        qIdx = Math.min(qIdx + 1, Q.length - 1);
                        qText.textContent = cleanQ(Q[qIdx]);
                        answers[qIdx - 1] = (qInput.value || '').trim();
                        qInput.value = answers[qIdx] || '';
                        qFly.animate(
                            [
                                { transform: 'translateX(100px) scale(.92)', opacity: 0, filter: 'blur(4px)' },
                                { transform: 'translateX(0) scale(1)', opacity: 1, filter: 'blur(0)' }
                            ],
                            { duration: 420, easing: SPRING_IN, fill: 'both' }
                        ).onfinish = () => {
                            isAnimating = false;
                            qNext.disabled = false;
                        };
                    };
                }


                qNext.addEventListener('click', (e) => {
                    e.stopPropagation();
                    nextQuestion();
                });

                qText.textContent = Q[qIdx];

                document.addEventListener('DOMContentLoaded', () => {
                    const warnOverlay = document.getElementById('warnOverlay');
                    const btnWarnBack = document.getElementById('btnWarnBack');
                    const btnWarnContinue = document.getElementById('btnWarnContinue');
                    const warnDontShowEl = document.getElementById('warnDontShow');
                    const qFlyPanel = document.getElementById('qFly');

                    if (!warnOverlay) return;

                    try {
                        const ok = localStorage.getItem('mirrorConsent');
                        if (ok === 'yes') {
                            warnOverlay.classList.add('hidden');
                            qFlyPanel?.classList.remove('hidden');
                        }
                    } catch (e) {
                    }

                    if (btnWarnBack) {
                        btnWarnBack.addEventListener('click', () => {
                            window.location.href = "{{ route('entrevistas.index') }}";
                        });
                    }

                    if (btnWarnContinue) {
                        btnWarnContinue.addEventListener('click', () => {
                            if (warnDontShowEl?.checked) {
                                try { localStorage.setItem('mirrorConsent', 'yes'); } catch { }
                            }
                            warnOverlay.classList.add('hidden');
                            qFlyPanel?.classList.remove('hidden');
                        });
                    }
                });

                function showFinalModal() {
                    document.getElementById('finalOverlay').classList.remove('hidden');
                    document.body.classList.add('modal-open');
                }
                document.getElementById('btnContinuarFinal').onclick = () => {
                    document.body.classList.remove('modal-open');
                    window.location.href = "/netrevistas";
                };
                function cleanQ(s) {
                    return String(s || '').replace(/\s*CONTINUAR\s*$/i, '').trim();
                }

                function renderQ() {
                    qText.textContent = cleanQ(Q[qIdx]);

                }
                function isLast() {
                    return qIdx >= Q.length - 1;
                }


                function showFinalModal() {
                    const overlay = document.getElementById('finalOverlay');
                    overlay.classList.remove('hidden');
                    document.body.classList.add('modal-open');

                    document.getElementById('btnContinuarFinal').onclick = () => {
                        document.body.classList.remove('modal-open');
                        window.location.href = "{{ route('detras.many', ['ids' => '11,12,13']) }}";
                    };
                }



            })();
        </script>
</body>

</html>
