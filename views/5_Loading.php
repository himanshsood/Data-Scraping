<!-- Fullscreen Loading Overlay -->
<div id="loadingOverlay" style="
  position: fixed;
  inset: 0;
    background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(5px);
  z-index: 9999;
  /* display: flex; */
  justify-content: center;
  align-items: center;
  display:none;
">
  <div style="
    display: flex;
    justify-content: space-between;
    width: 60px;
  ">
    <div style="
      width: 15px;
      height: 15px;
      background-color: #28a745;
      border-radius: 50%;
      animation: bounce 1.4s infinite ease-in-out both;
      animation-delay: -0.32s;
    "></div>
    <div style="
      width: 15px;
      height: 15px;
      background-color: #28a745;
      border-radius: 50%;
      animation: bounce 1.4s infinite ease-in-out both;
      animation-delay: -0.16s;
    "></div>
    <div style="
      width: 15px;
      height: 15px;
      background-color: #28a745;
      border-radius: 50%;
      animation: bounce 1.4s infinite ease-in-out both;
      animation-delay: 0;
    "></div>
  </div>
</div>

<style>
@keyframes bounce {
  0%, 80%, 100% {
    transform: scale(0);
  }
  40% {
    transform: scale(1);
  }
}
</style>