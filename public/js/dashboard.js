document.addEventListener("DOMContentLoaded", function () {
    const canvas = document.getElementById("payrollChart");

    // Jika tidak ada canvas (misal yang login adalah Karyawan, bukan Admin), hentikan script
    if (!canvas) return;

    const ctx = canvas.getContext("2d");

    // Ambil data yang dititipkan di HTML dan ubah kembali menjadi Array JavaScript
    const labels = JSON.parse(canvas.dataset.labels);
    const data = JSON.parse(canvas.dataset.values);

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Total Gaji Bersih (Take Home Pay)",
                    data: data,
                    backgroundColor: "rgba(201,168,76,0.18)",
                    borderColor: "rgba(201,168,76,0.75)",
                    borderWidth: 1.5,
                    borderRadius: 6,
                    hoverBackgroundColor: "rgba(226,196,122,0.28)",
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: { color: "rgba(255,255,255,0.04)" },
                    ticks: {
                        color: "rgba(240,237,230,0.4)",
                        font: { family: "DM Sans", size: 12 },
                    },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: "rgba(255,255,255,0.04)" },
                    ticks: {
                        color: "rgba(240,237,230,0.4)",
                        font: { family: "DM Sans", size: 12 },
                        callback: function (value) {
                            return "Rp " + value.toLocaleString("id-ID");
                        },
                    },
                },
            },
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: "#12151F",
                borderColor: "rgba(201,168,76,0.3)",
                borderWidth: 1,
                titleColor: "rgba(240,237,230,0.5)",
                bodyColor: "#E2C47A",
                titleFont: { family: "DM Sans", size: 11 },
                bodyFont: { family: "Cormorant Garant", size: 16 },
                callbacks: {
                    label: function (context) {
                        let label = context.dataset.label || "";
                        if (label) label += ": ";
                        if (context.parsed.y !== null) {
                            label +=
                                "Rp " +
                                context.parsed.y.toLocaleString("id-ID");
                        }
                        return label;
                    },
                },
            },
        },
    });
});
