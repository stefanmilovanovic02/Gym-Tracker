let currentDate = new Date();
document.getElementById("date-display").textContent =
  currentDate.toDateString();

function changeDate(days) {
  currentDate.setDate(currentDate.getDate() + days);
  document.getElementById("date-display").textContent =
    currentDate.toDateString();
  // Update progress based on the new date
  updateProgress();
}

function updateProgress() {
  // Fetch the progress data based on the current date
  // For now, let's just simulate the progress
  const progressData = {
    calories: Math.random() * 100,
    protein: Math.random() * 100,
    carbs: Math.random() * 100,
    fat: Math.random() * 100,
    creatine: Math.random() * 100,
    water: Math.random() * 100,
    exercise: Math.random() * 100,
  };

  document.getElementById("calories-progress").style.width =
    progressData.calories + "%";
  document.getElementById("calories-progress").textContent =
    Math.round(progressData.calories) + "%";
  document.getElementById("protein-progress").style.width =
    progressData.protein + "%";
  document.getElementById("protein-progress").textContent =
    Math.round(progressData.protein) + "%";
  document.getElementById("carbs-progress").style.width =
    progressData.carbs + "%";
  document.getElementById("carbs-progress").textContent =
    Math.round(progressData.carbs) + "%";
  document.getElementById("fat-progress").style.width = progressData.fat + "%";
  document.getElementById("fat-progress").textContent =
    Math.round(progressData.fat) + "%";
  document.getElementById("creatine-progress").style.width =
    progressData.creatine + "%";
  document.getElementById("creatine-progress").textContent =
    Math.round(progressData.creatine) + "%";
  document.getElementById("water-progress").style.width =
    progressData.water + "%";
  document.getElementById("water-progress").textContent =
    Math.round(progressData.water) + "%";
  document.getElementById("exercise-progress").style.width =
    progressData.exercise + "%";
  document.getElementById("exercise-progress").textContent =
    Math.round(progressData.exercise) + "%";

  updateChart(progressData);
}

function updateChart(progressData) {
  const ctx = document.getElementById("lineChart").getContext("2d");
  const chart = new Chart(ctx, {
    type: "line",
    data: {
      labels: [
        "Calories",
        "Protein",
        "Carbs",
        "Fat",
        "Creatine",
        "Water",
        "Exercise",
      ],
      datasets: [
        {
          label: "Progress",
          data: [
            progressData.calories,
            progressData.protein,
            progressData.carbs,
            progressData.fat,
            progressData.creatine,
            progressData.water,
            progressData.exercise,
          ],
          borderColor: "rgba(75, 192, 192, 1)",
          borderWidth: 2,
          fill: false,
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          max: 100,
        },
      },
    },
  });
}

function redirectToChartPage() {
  // Redirect to the detailed chart page
  window.location.href = "chart-page.html";
}

// Initial progress update
updateProgress();
