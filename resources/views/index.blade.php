<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="icon"
      href="/assets/brave-talk-192x192-403d6b23.png"
      type="image/png"
    />
    <title>Brave Talk</title>
    <script
      type="module"
      crossorigin=""
      src="/assets/index-ae99c0f7.js"
    ></script>
    <link rel="stylesheet" href="/assets/index-0506c3ee.css" />
    <style type="text/css">
      [data-sonner-toaster][dir="ltr"],
      html[dir="ltr"] {
        --toast-icon-margin-start: -3px;
        --toast-icon-margin-end: 4px;
        --toast-svg-margin-start: -1px;
        --toast-svg-margin-end: 0px;
        --toast-button-margin-start: auto;
        --toast-button-margin-end: 0;
        --toast-close-button-start: 0;
        --toast-close-button-end: unset;
        --toast-close-button-transform: translate(-35%, -35%);
      }
      [data-sonner-toaster][dir="rtl"],
      html[dir="rtl"] {
        --toast-icon-margin-start: 4px;
        --toast-icon-margin-end: -3px;
        --toast-svg-margin-start: 0px;
        --toast-svg-margin-end: -1px;
        --toast-button-margin-start: 0;
        --toast-button-margin-end: auto;
        --toast-close-button-start: unset;
        --toast-close-button-end: 0;
        --toast-close-button-transform: translate(35%, -35%);
      }
      [data-sonner-toaster] {
        position: fixed;
        width: var(--width);
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
          Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif,
          Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
        --gray1: hsl(0, 0%, 99%);
        --gray2: hsl(0, 0%, 97.3%);
        --gray3: hsl(0, 0%, 95.1%);
        --gray4: hsl(0, 0%, 93%);
        --gray5: hsl(0, 0%, 90.9%);
        --gray6: hsl(0, 0%, 88.7%);
        --gray7: hsl(0, 0%, 85.8%);
        --gray8: hsl(0, 0%, 78%);
        --gray9: hsl(0, 0%, 56.1%);
        --gray10: hsl(0, 0%, 52.3%);
        --gray11: hsl(0, 0%, 43.5%);
        --gray12: hsl(0, 0%, 9%);
        --border-radius: 8px;
        box-sizing: border-box;
        padding: 0;
        margin: 0;
        list-style: none;
        outline: 0;
        z-index: 999999999;
        transition: transform 0.4s ease;
      }
      @media (hover: none) and (pointer: coarse) {
        [data-sonner-toaster][data-lifted="true"] {
          transform: none;
        }
      }
      [data-sonner-toaster][data-x-position="right"] {
        right: var(--offset-right);
      }
      [data-sonner-toaster][data-x-position="left"] {
        left: var(--offset-left);
      }
      [data-sonner-toaster][data-x-position="center"] {
        left: 50%;
        transform: translateX(-50%);
      }
      [data-sonner-toaster][data-y-position="top"] {
        top: var(--offset-top);
      }
      [data-sonner-toaster][data-y-position="bottom"] {
        bottom: var(--offset-bottom);
      }
      [data-sonner-toast] {
        --y: translateY(100%);
        --lift-amount: calc(var(--lift) * var(--gap));
        z-index: var(--z-index);
        position: absolute;
        opacity: 0;
        transform: var(--y);
        touch-action: none;
        transition: transform 0.4s, opacity 0.4s, height 0.4s, box-shadow 0.2s;
        box-sizing: border-box;
        outline: 0;
        overflow-wrap: anywhere;
      }
      [data-sonner-toast][data-styled="true"] {
        padding: 16px;
        background: var(--normal-bg);
        border: 1px solid var(--normal-border);
        color: var(--normal-text);
        border-radius: var(--border-radius);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: var(--width);
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
      }
      [data-sonner-toast]:focus-visible {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1), 0 0 0 2px rgba(0, 0, 0, 0.2);
      }
      [data-sonner-toast][data-y-position="top"] {
        top: 0;
        --y: translateY(-100%);
        --lift: 1;
        --lift-amount: calc(1 * var(--gap));
      }
      [data-sonner-toast][data-y-position="bottom"] {
        bottom: 0;
        --y: translateY(100%);
        --lift: -1;
        --lift-amount: calc(var(--lift) * var(--gap));
      }
      [data-sonner-toast][data-styled="true"] [data-description] {
        font-weight: 400;
        line-height: 1.4;
        color: #3f3f3f;
      }
      [data-rich-colors="true"][data-sonner-toast][data-styled="true"]
        [data-description] {
        color: inherit;
      }
      [data-sonner-toaster][data-sonner-theme="dark"] [data-description] {
        color: #e8e8e8;
      }
      [data-sonner-toast][data-styled="true"] [data-title] {
        font-weight: 500;
        line-height: 1.5;
        color: inherit;
      }
      [data-sonner-toast][data-styled="true"] [data-icon] {
        display: flex;
        height: 16px;
        width: 16px;
        position: relative;
        justify-content: flex-start;
        align-items: center;
        flex-shrink: 0;
        margin-left: var(--toast-icon-margin-start);
        margin-right: var(--toast-icon-margin-end);
      }
      [data-sonner-toast][data-promise="true"] [data-icon] > svg {
        opacity: 0;
        transform: scale(0.8);
        transform-origin: center;
        animation: sonner-fade-in 0.3s ease forwards;
      }
      [data-sonner-toast][data-styled="true"] [data-icon] > * {
        flex-shrink: 0;
      }
      [data-sonner-toast][data-styled="true"] [data-icon] svg {
        margin-left: var(--toast-svg-margin-start);
        margin-right: var(--toast-svg-margin-end);
      }
      [data-sonner-toast][data-styled="true"] [data-content] {
        display: flex;
        flex-direction: column;
        gap: 2px;
      }
      [data-sonner-toast][data-styled="true"] [data-button] {
        border-radius: 4px;
        padding-left: 8px;
        padding-right: 8px;
        height: 24px;
        font-size: 12px;
        color: var(--normal-bg);
        background: var(--normal-text);
        margin-left: var(--toast-button-margin-start);
        margin-right: var(--toast-button-margin-end);
        border: none;
        font-weight: 500;
        cursor: pointer;
        outline: 0;
        display: flex;
        align-items: center;
        flex-shrink: 0;
        transition: opacity 0.4s, box-shadow 0.2s;
      }
      [data-sonner-toast][data-styled="true"] [data-button]:focus-visible {
        box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.4);
      }
      [data-sonner-toast][data-styled="true"] [data-button]:first-of-type {
        margin-left: var(--toast-button-margin-start);
        margin-right: var(--toast-button-margin-end);
      }
      [data-sonner-toast][data-styled="true"] [data-cancel] {
        color: var(--normal-text);
        background: rgba(0, 0, 0, 0.08);
      }
      [data-sonner-toaster][data-sonner-theme="dark"]
        [data-sonner-toast][data-styled="true"]
        [data-cancel] {
        background: rgba(255, 255, 255, 0.3);
      }
      [data-sonner-toast][data-styled="true"] [data-close-button] {
        position: absolute;
        left: var(--toast-close-button-start);
        right: var(--toast-close-button-end);
        top: 0;
        height: 20px;
        width: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0;
        color: var(--gray12);
        background: var(--normal-bg);
        border: 1px solid var(--gray4);
        transform: var(--toast-close-button-transform);
        border-radius: 50%;
        cursor: pointer;
        z-index: 1;
        transition: opacity 0.1s, background 0.2s, border-color 0.2s;
      }
      [data-sonner-toast][data-styled="true"]
        [data-close-button]:focus-visible {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1), 0 0 0 2px rgba(0, 0, 0, 0.2);
      }
      [data-sonner-toast][data-styled="true"] [data-disabled="true"] {
        cursor: not-allowed;
      }
      [data-sonner-toast][data-styled="true"]:hover [data-close-button]:hover {
        background: var(--gray2);
        border-color: var(--gray5);
      }
      [data-sonner-toast][data-swiping="true"]::before {
        content: "";
        position: absolute;
        left: -100%;
        right: -100%;
        height: 100%;
        z-index: -1;
      }
      [data-sonner-toast][data-y-position="top"][data-swiping="true"]::before {
        bottom: 50%;
        transform: scaleY(3) translateY(50%);
      }
      [data-sonner-toast][data-y-position="bottom"][data-swiping="true"]::before {
        top: 50%;
        transform: scaleY(3) translateY(-50%);
      }
      [data-sonner-toast][data-swiping="false"][data-removed="true"]::before {
        content: "";
        position: absolute;
        inset: 0;
        transform: scaleY(2);
      }
      [data-sonner-toast][data-expanded="true"]::after {
        content: "";
        position: absolute;
        left: 0;
        height: calc(var(--gap) + 1px);
        bottom: 100%;
        width: 100%;
      }
      [data-sonner-toast][data-mounted="true"] {
        --y: translateY(0);
        opacity: 1;
      }
      [data-sonner-toast][data-expanded="false"][data-front="false"] {
        --scale: var(--toasts-before) * 0.05 + 1;
        --y: translateY(calc(var(--lift-amount) * var(--toasts-before)))
          scale(calc(-1 * var(--scale)));
        height: var(--front-toast-height);
      }
      [data-sonner-toast] > * {
        transition: opacity 0.4s;
      }
      [data-sonner-toast][data-x-position="right"] {
        right: 0;
      }
      [data-sonner-toast][data-x-position="left"] {
        left: 0;
      }
      [data-sonner-toast][data-expanded="false"][data-front="false"][data-styled="true"]
        > * {
        opacity: 0;
      }
      [data-sonner-toast][data-visible="false"] {
        opacity: 0;
        pointer-events: none;
      }
      [data-sonner-toast][data-mounted="true"][data-expanded="true"] {
        --y: translateY(calc(var(--lift) * var(--offset)));
        height: var(--initial-height);
      }
      [data-sonner-toast][data-removed="true"][data-front="true"][data-swipe-out="false"] {
        --y: translateY(calc(var(--lift) * -100%));
        opacity: 0;
      }
      [data-sonner-toast][data-removed="true"][data-front="false"][data-swipe-out="false"][data-expanded="true"] {
        --y: translateY(
          calc(var(--lift) * var(--offset) + var(--lift) * -100%)
        );
        opacity: 0;
      }
      [data-sonner-toast][data-removed="true"][data-front="false"][data-swipe-out="false"][data-expanded="false"] {
        --y: translateY(40%);
        opacity: 0;
        transition: transform 0.5s, opacity 0.2s;
      }
      [data-sonner-toast][data-removed="true"][data-front="false"]::before {
        height: calc(var(--initial-height) + 20%);
      }
      [data-sonner-toast][data-swiping="true"] {
        transform: var(--y) translateY(var(--swipe-amount-y, 0))
          translateX(var(--swipe-amount-x, 0));
        transition: none;
      }
      [data-sonner-toast][data-swiped="true"] {
        user-select: none;
      }
      [data-sonner-toast][data-swipe-out="true"][data-y-position="bottom"],
      [data-sonner-toast][data-swipe-out="true"][data-y-position="top"] {
        animation-duration: 0.2s;
        animation-timing-function: ease-out;
        animation-fill-mode: forwards;
      }
      [data-sonner-toast][data-swipe-out="true"][data-swipe-direction="left"] {
        animation-name: swipe-out-left;
      }
      [data-sonner-toast][data-swipe-out="true"][data-swipe-direction="right"] {
        animation-name: swipe-out-right;
      }
      [data-sonner-toast][data-swipe-out="true"][data-swipe-direction="up"] {
        animation-name: swipe-out-up;
      }
      [data-sonner-toast][data-swipe-out="true"][data-swipe-direction="down"] {
        animation-name: swipe-out-down;
      }
      @keyframes swipe-out-left {
        from {
          transform: var(--y) translateX(var(--swipe-amount-x));
          opacity: 1;
        }
        to {
          transform: var(--y) translateX(calc(var(--swipe-amount-x) - 100%));
          opacity: 0;
        }
      }
      @keyframes swipe-out-right {
        from {
          transform: var(--y) translateX(var(--swipe-amount-x));
          opacity: 1;
        }
        to {
          transform: var(--y) translateX(calc(var(--swipe-amount-x) + 100%));
          opacity: 0;
        }
      }
      @keyframes swipe-out-up {
        from {
          transform: var(--y) translateY(var(--swipe-amount-y));
          opacity: 1;
        }
        to {
          transform: var(--y) translateY(calc(var(--swipe-amount-y) - 100%));
          opacity: 0;
        }
      }
      @keyframes swipe-out-down {
        from {
          transform: var(--y) translateY(var(--swipe-amount-y));
          opacity: 1;
        }
        to {
          transform: var(--y) translateY(calc(var(--swipe-amount-y) + 100%));
          opacity: 0;
        }
      }
      @media (max-width: 600px) {
        [data-sonner-toaster] {
          position: fixed;
          right: var(--mobile-offset-right);
          left: var(--mobile-offset-left);
          width: 100%;
        }
        [data-sonner-toaster][dir="rtl"] {
          left: calc(var(--mobile-offset-left) * -1);
        }
        [data-sonner-toaster] [data-sonner-toast] {
          left: 0;
          right: 0;
          width: calc(100% - var(--mobile-offset-left) * 2);
        }
        [data-sonner-toaster][data-x-position="left"] {
          left: var(--mobile-offset-left);
        }
        [data-sonner-toaster][data-y-position="bottom"] {
          bottom: var(--mobile-offset-bottom);
        }
        [data-sonner-toaster][data-y-position="top"] {
          top: var(--mobile-offset-top);
        }
        [data-sonner-toaster][data-x-position="center"] {
          left: var(--mobile-offset-left);
          right: var(--mobile-offset-right);
          transform: none;
        }
      }
      [data-sonner-toaster][data-sonner-theme="light"] {
        --normal-bg: #fff;
        --normal-border: var(--gray4);
        --normal-text: var(--gray12);
        --success-bg: hsl(143, 85%, 96%);
        --success-border: hsl(145, 92%, 87%);
        --success-text: hsl(140, 100%, 27%);
        --info-bg: hsl(208, 100%, 97%);
        --info-border: hsl(221, 91%, 93%);
        --info-text: hsl(210, 92%, 45%);
        --warning-bg: hsl(49, 100%, 97%);
        --warning-border: hsl(49, 91%, 84%);
        --warning-text: hsl(31, 92%, 45%);
        --error-bg: hsl(359, 100%, 97%);
        --error-border: hsl(359, 100%, 94%);
        --error-text: hsl(360, 100%, 45%);
      }
      [data-sonner-toaster][data-sonner-theme="light"]
        [data-sonner-toast][data-invert="true"] {
        --normal-bg: #000;
        --normal-border: hsl(0, 0%, 20%);
        --normal-text: var(--gray1);
      }
      [data-sonner-toaster][data-sonner-theme="dark"]
        [data-sonner-toast][data-invert="true"] {
        --normal-bg: #fff;
        --normal-border: var(--gray3);
        --normal-text: var(--gray12);
      }
      [data-sonner-toaster][data-sonner-theme="dark"] {
        --normal-bg: #000;
        --normal-bg-hover: hsl(0, 0%, 12%);
        --normal-border: hsl(0, 0%, 20%);
        --normal-border-hover: hsl(0, 0%, 25%);
        --normal-text: var(--gray1);
        --success-bg: hsl(150, 100%, 6%);
        --success-border: hsl(147, 100%, 12%);
        --success-text: hsl(150, 86%, 65%);
        --info-bg: hsl(215, 100%, 6%);
        --info-border: hsl(223, 43%, 17%);
        --info-text: hsl(216, 87%, 65%);
        --warning-bg: hsl(64, 100%, 6%);
        --warning-border: hsl(60, 100%, 9%);
        --warning-text: hsl(46, 87%, 65%);
        --error-bg: hsl(358, 76%, 10%);
        --error-border: hsl(357, 89%, 16%);
        --error-text: hsl(358, 100%, 81%);
      }
      [data-sonner-toaster][data-sonner-theme="dark"]
        [data-sonner-toast]
        [data-close-button] {
        background: var(--normal-bg);
        border-color: var(--normal-border);
        color: var(--normal-text);
      }
      [data-sonner-toaster][data-sonner-theme="dark"]
        [data-sonner-toast]
        [data-close-button]:hover {
        background: var(--normal-bg-hover);
        border-color: var(--normal-border-hover);
      }
      [data-rich-colors="true"][data-sonner-toast][data-type="success"] {
        background: var(--success-bg);
        border-color: var(--success-border);
        color: var(--success-text);
      }
      [data-rich-colors="true"][data-sonner-toast][data-type="success"]
        [data-close-button] {
        background: var(--success-bg);
        border-color: var(--success-border);
        color: var(--success-text);
      }
      [data-rich-colors="true"][data-sonner-toast][data-type="info"] {
        background: var(--info-bg);
        border-color: var(--info-border);
        color: var(--info-text);
      }
      [data-rich-colors="true"][data-sonner-toast][data-type="info"]
        [data-close-button] {
        background: var(--info-bg);
        border-color: var(--info-border);
        color: var(--info-text);
      }
      [data-rich-colors="true"][data-sonner-toast][data-type="warning"] {
        background: var(--warning-bg);
        border-color: var(--warning-border);
        color: var(--warning-text);
      }
      [data-rich-colors="true"][data-sonner-toast][data-type="warning"]
        [data-close-button] {
        background: var(--warning-bg);
        border-color: var(--warning-border);
        color: var(--warning-text);
      }
      [data-rich-colors="true"][data-sonner-toast][data-type="error"] {
        background: var(--error-bg);
        border-color: var(--error-border);
        color: var(--error-text);
      }
      [data-rich-colors="true"][data-sonner-toast][data-type="error"]
        [data-close-button] {
        background: var(--error-bg);
        border-color: var(--error-border);
        color: var(--error-text);
      }
      .sonner-loading-wrapper {
        --size: 16px;
        height: var(--size);
        width: var(--size);
        position: absolute;
        inset: 0;
        z-index: 10;
      }
      .sonner-loading-wrapper[data-visible="false"] {
        transform-origin: center;
        animation: sonner-fade-out 0.2s ease forwards;
      }
      .sonner-spinner {
        position: relative;
        top: 50%;
        left: 50%;
        height: var(--size);
        width: var(--size);
      }
      .sonner-loading-bar {
        animation: sonner-spin 1.2s linear infinite;
        background: var(--gray11);
        border-radius: 6px;
        height: 8%;
        left: -10%;
        position: absolute;
        top: -3.9%;
        width: 24%;
      }
      .sonner-loading-bar:first-child {
        animation-delay: -1.2s;
        transform: rotate(0.0001deg) translate(146%);
      }
      .sonner-loading-bar:nth-child(2) {
        animation-delay: -1.1s;
        transform: rotate(30deg) translate(146%);
      }
      .sonner-loading-bar:nth-child(3) {
        animation-delay: -1s;
        transform: rotate(60deg) translate(146%);
      }
      .sonner-loading-bar:nth-child(4) {
        animation-delay: -0.9s;
        transform: rotate(90deg) translate(146%);
      }
      .sonner-loading-bar:nth-child(5) {
        animation-delay: -0.8s;
        transform: rotate(120deg) translate(146%);
      }
      .sonner-loading-bar:nth-child(6) {
        animation-delay: -0.7s;
        transform: rotate(150deg) translate(146%);
      }
      .sonner-loading-bar:nth-child(7) {
        animation-delay: -0.6s;
        transform: rotate(180deg) translate(146%);
      }
      .sonner-loading-bar:nth-child(8) {
        animation-delay: -0.5s;
        transform: rotate(210deg) translate(146%);
      }
      .sonner-loading-bar:nth-child(9) {
        animation-delay: -0.4s;
        transform: rotate(240deg) translate(146%);
      }
      .sonner-loading-bar:nth-child(10) {
        animation-delay: -0.3s;
        transform: rotate(270deg) translate(146%);
      }
      .sonner-loading-bar:nth-child(11) {
        animation-delay: -0.2s;
        transform: rotate(300deg) translate(146%);
      }
      .sonner-loading-bar:nth-child(12) {
        animation-delay: -0.1s;
        transform: rotate(330deg) translate(146%);
      }
      @keyframes sonner-fade-in {
        0% {
          opacity: 0;
          transform: scale(0.8);
        }
        100% {
          opacity: 1;
          transform: scale(1);
        }
      }
      @keyframes sonner-fade-out {
        0% {
          opacity: 1;
          transform: scale(1);
        }
        100% {
          opacity: 0;
          transform: scale(0.8);
        }
      }
      @keyframes sonner-spin {
        0% {
          opacity: 1;
        }
        100% {
          opacity: 0.15;
        }
      }
      @media (prefers-reduced-motion) {
        .sonner-loading-bar,
        [data-sonner-toast],
        [data-sonner-toast] > * {
          transition: none !important;
          animation: none !important;
        }
      }
      .sonner-loader {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        transform-origin: center;
        transition: opacity 0.2s, transform 0.2s;
      }
      .sonner-loader[data-visible="false"] {
        opacity: 0;
        transform: scale(0.8) translate(-50%, -50%);
      }
    </style>
  </head>
  <body>
    <div id="root">
      <div class="flex min-h-screen bg-black">
        <div
          class="w-full md:w-1/4 bg-gradient-to-br from-purple-700 to-pink-500 text-white p-10 flex flex-col justify-between relative"
          style="
            background-image: url('/assets/background-77c3d38e.svg');
            background-position: center center;
            background-size: cover;
            width: 415px;
            background-repeat: no-repeat;
          "
        >
          <div class="absolute top-9 left-9 z-10">
            <img
              src="/assets/brave_logo_dark-2be8a5e6.svg"
              alt="Logo"
              class="max-w-[70px] max-h-[35px]"
            />
          </div>
          <div class="flex flex-col items-center justify-center flex-1 px-4">
            <h2 class="text-3xl font-bold mb-1 text-center">Join meeting</h2>
            <p class="text-lg font-bold mb-6 text-center">Brave Talk</p>
            <input
              type="text"
              placeholder="Enter your name"
              class="w-full rounded mb-2 focus:outline-none transition text-center"
              value="апыпыаывафы"
              style="
                height: 48px;
                padding: 13px 16px;
                font-size: 16px;
                line-height: 22px;
                font-weight: 400;
                letter-spacing: 0px;
                background: rgb(61, 61, 61);
                color: rgb(255, 255, 255);
                box-shadow: none;
              "
            /><button
              class="w-full py-2 rounded font-semibold transition relative text-center bg-[rgb(70,135,237)] text-white hover:bg-blue-600"
            >
              Join meeting<span
                class="absolute right-4 top-1/2 -translate-y-1/2"
                ><svg
                  aria-hidden="true"
                  height="24"
                  width="24"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                  class="fill-current"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M3.97 7.72a.75.75 0 0 1 1.06 0L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 0-1.06Z"
                  ></path></svg
              ></span>
            </button>
            <div
              class="flex w-full justify-between mt-10 text-white text-xl px-4"
            >
              <button>
                <div class="toolbox-button" role="button" tabindex="0">
                  <span class="tooltip">Unmute microphone</span>
                  <div class="toolbox-icon toggled">
                    <div class="toolbox-icon toggled">
                      <div class="jitsi-icon jitsi-icon-default">
                        <svg
                          aria-hidden="true"
                          height="24"
                          fill="#fff"
                          width="24"
                          viewBox="0 0 24 24"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M16.5 6.44V6a4.5 4.5 0 1 0-9 0v6c0 .972.308 1.872.832 2.607L7.26 15.68A5.974 5.974 0 0 1 6 12v-1.5a.75.75 0 0 0-1.5 0V12c0 1.801.635 3.454 1.693 4.747L3.22 19.72a.75.75 0 1 0 1.06 1.06l16.5-16.5a.75.75 0 0 0-1.06-1.06L16.5 6.44ZM15 7.94V6a3 3 0 1 0-6 0v6c0 .556.151 1.077.415 1.524L15 7.939Z"
                          ></path>
                          <path
                            d="M9.79 17.58A6 6 0 0 0 18 12v-1.5a.75.75 0 0 1 1.5 0V12a7.501 7.501 0 0 1-6.75 7.463v2.287a.75.75 0 0 1-1.5 0v-2.287a7.452 7.452 0 0 1-2.188-.56c-.465-.199-.541-.799-.183-1.156.237-.238.6-.29.911-.167Z"
                          ></path>
                          <path
                            d="M15.75 11.25A.75.75 0 0 0 15 12a3 3 0 0 1-3 3 .75.75 0 0 0 0 1.5 4.5 4.5 0 0 0 4.5-4.5.75.75 0 0 0-.75-.75Z"
                          ></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                </div></button
              ><button>
                <div class="toolbox-button" role="button" tabindex="0">
                  <span class="tooltip">Start camera</span>
                  <div class="toolbox-icon toggled">
                    <div class="jitsi-icon jitsi-icon-default">
                      <svg
                        aria-hidden="true"
                        height="24"
                        fill="#fff"
                        width="24"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          fill-rule="evenodd"
                          clip-rule="evenodd"
                          d="M20.78 3.22a.75.75 0 0 1 0 1.06l-3.37 3.371.005-.004-1.665 1.665V9.31L7.06 18h.002l-1.5 1.5H5.56l-1.28 1.28a.75.75 0 0 1-1.061-1.06l.362-.363A3.001 3.001 0 0 1 1.5 16.5v-9a3 3 0 0 1 3-3h9.75a3 3 0 0 1 2.631 1.558L19.72 3.22a.75.75 0 0 1 1.06 0Zm-5.057 3.996A1.5 1.5 0 0 0 14.25 6H4.5A1.5 1.5 0 0 0 3 7.5v9A1.5 1.5 0 0 0 4.5 18h.44L15.722 7.216Z"
                        ></path>
                        <path
                          d="M21 6.75a.75.75 0 0 1 1.5 0v10.474c0 1.246-1.43 1.949-2.416 1.188l-2.834-2.186v.274a3 3 0 0 1-3 3H9A.75.75 0 0 1 9 18h5.25a1.5 1.5 0 0 0 1.5-1.5V12a.75.75 0 0 1 1.5 0v2.331L21 17.224V6.75Z"
                        ></path>
                      </svg>
                    </div>
                  </div>
                </div></button
              ><button>
                <div class="toolbox-button" role="button" tabindex="0">
                  <span class="tooltip">Invite people</span>
                  <div class="toolbox-icon">
                    <div class="jitsi-icon jitsi-icon-default">
                      <svg
                        aria-hidden="true"
                        height="24"
                        width="24"
                        fill="#fff"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M17.25 9.75a.75.75 0 0 0 1.5 0v-3h3a.75.75 0 0 0 0-1.5h-3v-3a.75.75 0 0 0-1.5 0v3h-3a.75.75 0 0 0 0 1.5h3v3Z"
                        ></path>
                        <path
                          fill-rule="evenodd"
                          clip-rule="evenodd"
                          d="M11.25 9.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Zm-1.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0ZM13.5 18.75c0 2.071-2.686 3.75-6 3.75s-6-1.679-6-3.75c0-2.071 2.686-3.75 6-3.75s6 1.679 6 3.75Zm-1.5 0c0 .332-.22.859-1.052 1.38-.812.507-2.027.87-3.448.87-1.42 0-2.636-.363-3.448-.87C3.22 19.609 3 19.082 3 18.75c0-.332.22-.859 1.052-1.38.812-.507 2.027-.87 3.448-.87 1.42 0 2.636.363 3.448.87.833.521 1.052 1.048 1.052 1.38Z"
                        ></path>
                      </svg>
                    </div>
                  </div>
                </div></button
              ><button>
                <div class="toolbox-button" role="button" tabindex="0">
                  <span class="tooltip">Select background</span>
                  <div class="toolbox-icon">
                    <div class="jitsi-icon jitsi-icon-default">
                      <svg
                        aria-hidden="true"
                        fill="#fff"
                        height="24"
                        width="24"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          fill-rule="evenodd"
                          clip-rule="evenodd"
                          d="M16.5 10.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0-1.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"
                        ></path>
                        <path
                          fill-rule="evenodd"
                          clip-rule="evenodd"
                          d="M1.5 15.75v3.75a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3v-15a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v11.25ZM19.5 3h-15A1.5 1.5 0 0 0 3 4.5v9.44l3.97-3.97a.75.75 0 0 1 1.06 0L12 13.94l1.72-1.72a.75.75 0 0 1 1.06 0L21 18.44V4.5A1.5 1.5 0 0 0 19.5 3Zm1.258 17.318-6.508-6.507-1.72 1.72a.75.75 0 0 1-1.06 0L7.5 11.56 3 16.06V19.5A1.5 1.5 0 0 0 4.5 21h15c.527 0 .99-.271 1.258-.682Z"
                        ></path>
                      </svg>
                    </div>
                  </div>
                </div></button
              ><button>
                <div class="toolbox-button" role="button" tabindex="0">
                  <span class="tooltip">Settings</span>
                  <div class="toolbox-icon">
                    <div class="jitsi-icon jitsi-icon-default">
                      <svg
                        aria-hidden="true"
                        height="24"
                        width="24"
                        fill="#fff"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          fill-rule="evenodd"
                          clip-rule="evenodd"
                          d="M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Zm-1.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"
                        ></path>
                        <path
                          fill-rule="evenodd"
                          clip-rule="evenodd"
                          d="M9.026 3.447c0-1.069.867-1.947 1.936-1.947h2.076c1.069 0 1.936.878 1.936 1.947 0 1.48 1.604 2.422 2.883 1.678.92-.536 2.1-.22 2.63.704l1.054 1.844c.531.927.214 2.109-.709 2.646-1.288.75-1.288 2.611 0 3.361.923.538 1.24 1.72.71 2.647l-1.056 1.844a1.918 1.918 0 0 1-2.63.704c-1.278-.744-2.882.198-2.882 1.677 0 1.07-.867 1.948-1.936 1.948h-2.076a1.946 1.946 0 0 1-1.936-1.948c0-1.479-1.604-2.421-2.883-1.677-.92.536-2.1.22-2.63-.704L2.46 16.327a1.945 1.945 0 0 1 .709-2.647c1.288-.75 1.288-2.61 0-3.36a1.945 1.945 0 0 1-.71-2.647l1.056-1.844a1.918 1.918 0 0 1 2.63-.704c1.278.744 2.882-.198 2.882-1.678ZM10.962 3h2.076c.229 0 .436.195.436.447 0 2.61 2.836 4.314 5.137 2.974a.418.418 0 0 1 .573.153l1.055 1.844a.445.445 0 0 1-.162.605c-2.281 1.33-2.281 4.625 0 5.954a.445.445 0 0 1 .162.605l-1.055 1.844a.418.418 0 0 1-.573.153c-2.301-1.34-5.137.363-5.137 2.973a.446.446 0 0 1-.436.448h-2.076a.446.446 0 0 1-.436-.448c0-2.61-2.836-4.313-5.138-2.973a.418.418 0 0 1-.572-.153L3.76 15.582a.445.445 0 0 1 .163-.605c2.281-1.33 2.281-4.625 0-5.954a.445.445 0 0 1-.163-.605l1.056-1.844a.418.418 0 0 1 .572-.153c2.302 1.34 5.138-.364 5.138-2.974 0-.252.207-.447.436-.447Z"
                        ></path>
                      </svg>
                    </div>
                  </div>
                </div>
              </button>
            </div>
          </div>
          <p
            class="text-[14px] leading-5 font-normal mb-4 px-4 justify-center flex text-center"
            style="color: rgb(133, 133, 133); letter-spacing: 0px"
          >
            Other participants may be recording this call
          </p>
        </div>
        <div class="hidden md:flex w-3/4 items-center justify-center bg-black">
          <div
            class="w-40 h-40 rounded-full flex items-center justify-center relative"
            style="
              width: 200px;
              height: 200px;
              background-color: rgb(168, 218, 220);
            "
          >
            <span class="text-white text-6xl z-10">А</span>
          </div>
        </div>
      </div>
      <section
        aria-label="Notifications alt+T"
        tabindex="-1"
        aria-live="polite"
        aria-relevant="additions text"
        aria-atomic="false"
      ></section>
    </div>
  </body>
</html>
