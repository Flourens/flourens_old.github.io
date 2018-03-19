function toTitleCase(str)
{
    return str.replace(/\w\S*/g, function(txt){
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        }
    );
}

function showAlert(alertType, text, alertId) {
    // text: Alert text
    // alertId: unique id for identify alert
    // alertType: success, info, warning, danger
    const alertTitle = toTitleCase(alertType);
    const alertTemplate = `
        <div class="alert alert-${alertType} alert-dismissible" role="alert" id=${alertId}>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>${alertTitle}: </strong>${text}
        </div>
    `;

    if ($(`#${alertId}`).length === 0) {
        $("#alerts").append($(alertTemplate));
    }
}
function checkAgentTime(agentData) {
    if ('agent_epoch_ms' in agentData)
    {
        const agentTimeStamp = moment(agentData.response_epoch_ms);
        const serverTimeStamp = moment(agentData.server_epoch_ms);
        const diffMinutes = serverTimeStamp.diff(agentTimeStamp, "minutes");

        if (diffMinutes > 2) {
            showAlert("danger", "The agent PC is reporting an incorrect time.  It is reporing a time that is " + diffMinutes + " minutes different from the server.", "wrong-clock-alert");
        }
    }

}

function checkAgentQueueSize(agentData) {
    if (agentData.agent_status.beanstalk_queue_size > 30) {
        const alertText = "Agent upload queue is congested. The web server may be unable to receive agent payload at a fast enough rate.";
        showAlert("warning", alertText, "big-queue-size-alert");
    }
}

function checkNumberOfVideoStreams(agentData) {
    const videoStreams = agentData.agent_status.video_streams.length;
    const numberOfActiveThreads = agentData.agent_status.processing_threads_active * 1.5;

    if (videoStreams > numberOfActiveThreads) {
        const alertText = `You have more video streams configured than 
CPU cores used for plate analysis.  For best results, try to have at least 
one CPU core available for every video stream that is being analyzed`;
        showAlert("warning", alertText, "too-many-active-threds");
    }
}

function checkAgentVersion(agentData, minVer) {
    if (agentData.version < minVer) {
        const alertText = `An agent upgrade is available. We recommend upgrading
to the latest agent version for the best possible performance and accuracy.`;
        showAlert("warning", alertText, "old-agent");
    }
}

function agentWarnings(agentData, minRecommendedAgentVer) {
    if (agentData.agent_status.alprd_active == true)
    {
        checkAgentQueueSize(agentData);
        checkNumberOfVideoStreams(agentData);
    }

    checkAgentTime(agentData);
    checkAgentVersion(agentData, minRecommendedAgentVer);
}
