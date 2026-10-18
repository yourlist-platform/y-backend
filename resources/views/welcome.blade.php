<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yourlist</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600|ibm-plex-mono:400,500" rel="stylesheet">
    <style>
        :root {
            --bg: #ffffff;
            --ink: #111111;
            --line: rgba(17, 17, 17, 0.1);
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            min-height: 100%;
            background: var(--bg);
            color: var(--ink);
        }

        body {
            min-height: 100vh;
            overflow: hidden;
            font-family: "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
        }

        .stage {
            position: relative;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background:
                linear-gradient(var(--line) 1px, transparent 1px),
                linear-gradient(90deg, var(--line) 1px, transparent 1px);
            background-size: 72px 72px;
            background-color: var(--bg);
        }

        .frame {
            position: absolute;
            inset: 22px;
            border: 1px solid var(--ink);
            pointer-events: none;
        }

        .frame::before,
        .frame::after {
            content: "";
            position: absolute;
            left: 18px;
            right: 18px;
            height: 1px;
            background: var(--ink);
        }

        .frame::before { top: 42px; }
        .frame::after { bottom: 42px; }

        .ticks {
            position: absolute;
            top: 78px;
            bottom: 78px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 36px;
            pointer-events: none;
        }

        .ticks.left { left: 34px; }
        .ticks.right { right: 34px; align-items: flex-end; }

        .ticks span {
            display: block;
            height: 1px;
            background: var(--ink);
        }

        .ticks.left span:nth-child(3n) { width: 28px; }
        .ticks.left span:nth-child(3n + 1) { width: 14px; }
        .ticks.left span:nth-child(3n + 2) { width: 8px; }
        .ticks.right span:nth-child(3n) { width: 28px; }
        .ticks.right span:nth-child(3n + 1) { width: 14px; }
        .ticks.right span:nth-child(3n + 2) { width: 8px; }

        .bars {
            position: absolute;
            top: 50%;
            display: grid;
            gap: 8px;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .bars.left { left: 78px; }
        .bars.right { right: 78px; justify-items: end; }

        .bars i {
            display: block;
            height: 4px;
            background: var(--ink);
        }

        .bars.left i:nth-child(1) { width: 72px; }
        .bars.left i:nth-child(2) { width: 40px; }
        .bars.left i:nth-child(3) { width: 96px; }
        .bars.left i:nth-child(4) { width: 28px; }
        .bars.left i:nth-child(5) { width: 64px; }

        .bars.right i:nth-child(1) { width: 48px; }
        .bars.right i:nth-child(2) { width: 88px; }
        .bars.right i:nth-child(3) { width: 32px; }
        .bars.right i:nth-child(4) { width: 70px; }
        .bars.right i:nth-child(5) { width: 44px; }

        h1 {
            position: relative;
            z-index: 1;
            margin: 0;
            padding: 0 12px;
            font-size: clamp(3.2rem, 10vw, 8.6rem);
            font-weight: 500;
            letter-spacing: -0.065em;
            line-height: 1;
            white-space: nowrap;
            color: var(--ink);
            background: var(--bg);
        }

        .chrome {
            position: absolute;
            z-index: 2;
            left: 40px;
            right: 40px;
            display: flex;
            justify-content: space-between;
            font-family: "IBM Plex Mono", ui-monospace, monospace;
            font-size: 11px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
        }

        .chrome.top { top: 34px; }
        .chrome.bottom { bottom: 34px; }

        .mark {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .sq {
            width: 7px;
            height: 7px;
            background: var(--ink);
            animation: blink 1.6s steps(1) infinite;
        }

        @keyframes blink {
            50% { opacity: 0.15; }
        }

        @media (max-width: 800px) {
            .bars { display: none; }
            .ticks { display: none; }
            .chrome { left: 32px; right: 32px; letter-spacing: 0.12em; font-size: 10px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .sq { animation: none; }
        }
    </style>
</head>
<body>
    <main class="stage">
        <div class="frame"></div>
        <div class="ticks left" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
        <div class="ticks right" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
        <div class="bars left" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i></div>
        <div class="bars right" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i></div>

        <div class="chrome top">
            <span>YL / 01</span>
            <span class="mark"><i class="sq"></i> Signal</span>
        </div>

        <h1>Yourlist</h1>

        <div class="chrome bottom">
            <span>IDX 001</span>
            <span>NODE A</span>
        </div>
    </main>
</body>
</html>
