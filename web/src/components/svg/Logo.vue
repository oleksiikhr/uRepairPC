<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 300 300"
  >
    <rect
      fill="transparent"
      x="0"
      y="0"
      width="300px"
      height="300px"
      class="logo-background-square"
    />
    <linearGradient
      id="logo"
      gradientTransform="rotate(25)"
      x1="0%"
      y1="0%"
      x2="100%"
      y2="0%"
    >
      <stop
        offset="0%"
        style="stop-color: rgb(81, 164, 251); stop-opacity: 1;"
      />
      <stop
        offset="100%"
        style="stop-color: rgb(81, 164, 251); stop-opacity: 1;"
      />
    </linearGradient>
    <g
      fill="url(#logo)"
      stroke="none"
      transform="matrix(0.7,0,0,0.7,45,45)"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 32 32"
      >
        <path
          d="M17.572 23.933l4.106 7.095L32 13.189h-8.981V7.582L11.51.972 0 7.582v13.222l11.51 6.611 6.062-3.482zm10.96-8.744l-6.855 11.846-6.855-11.846h13.71z"
        />
      </svg>
    </g>
  </svg>
</template>
