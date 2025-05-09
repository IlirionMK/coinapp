 <template>
    <div class="flex items-center gap-3">
        <svg
            class="w-10 h-10"
            viewBox="0 0 100 100"
            xmlns="http://www.w3.org/2000/svg"
        >
            <circle cx="50" cy="50" r="48" fill="#0f172a" stroke="#fff" stroke-width="4" />
            <path d="M50,50 L85,35 A45,45 0 0,1 65,85 Z" fill="#f7931a" /> <!-- BTC -->
            <path d="M50,50 L65,85 A45,45 0 0,1 35,85 Z" fill="#26a17b" /> <!-- USDT -->
            <path d="M50,50 L35,85 A45,45 0 0,1 15,35 Z" fill="#00aaff" /> <!-- XRP -->
            <path d="M50,50 L15,35 A45,45 0 0,1 85,35 Z" fill="#627eea" /> <!-- ETH -->
            <circle cx="50" cy="50" r="14" fill="#ffffff" stroke="#000000" stroke-width="2" />
            <text
                x="50%" y="54%"
                text-anchor="middle"
                fill="#000000"
                font-size="16"
                font-weight="bold"
                font-family="Arial"
            >
                C
            </text>
        </svg>
        <span class="text-2xl font-bold text-white">CoinApp</span>
    </div>
</template>
