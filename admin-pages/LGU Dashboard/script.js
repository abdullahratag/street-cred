
function updateStatus(btn){
  let row = btn.parentElement.parentElement;
  let status = row.children[3].querySelector("span");

  if(status.classList.contains("pending")){
    status.className = "badge progress";
    status.innerText = "In Progress";
  } else if(status.classList.contains("progress")){
    status.className = "badge done";
    status.innerText = "Resolved";
    btn.innerText = "Done";
    btn.disabled = true;
  }
}