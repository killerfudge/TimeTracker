'use strict' 

// Get increase campaign time
var clone;
const wrapper = document.getElementById('wrapper');
wrapper.addEventListener('click', (event) => {
    const isButton = event.target.nodeName === 'BUTTON';
    if (!isButton) 
    {
        return;
    }
    let time = event.target.id;
    console.log(`time is: ${time}`);
    let campaignId = document.getElementById('campaignId');
    console.log(`campaignId is: ${campaignId.value}`);
    // if($_SERVER['HTTP_HOST'] == 'localhost')
    // {
    //     let campaignIdURL = "/TimeTracker/campaign/index.php?action=getCampaignInfo&campaignId=" + campaignId.value;
    // }
    // else
    // {
        let campaignIdURL = "/campaign/index.php?action=getCampaignInfo&campaignId=" + campaignId.value;
    //} 
    fetch(campaignIdURL)
    .then(function (response) 
    { 
        clone = response.clone();
        if (response.ok)
        { 
            return response.json(); 
        } 
        throw Error("Network response was not OK"); 
    }) 
    .then(function (data) 
    {    
        console.log(data); 
        if(time == 'secs')
        {
            data.currentSeconds += 6;
            progressTime(data);
        }
        else if(time == 'min')
        {
            data.currentMinutes += 1;
            progressTime(data);
        }
        else if(time == 'mins')
        {
            data.currentMinutes += 30;
            progressTime(data);
        }
        else if(time == 'hour')
        {
            data.currentHours += 1;
            progressTime(data);
        }
        else if(time == 'hours')
        {
            data.currentHours += 8;
            progressTime(data);
        }
        console.log("bottom");
        progressTrackers(data.campaignId, time);
    }) 
    .catch(function (error) 
    { 
        console.log('There was a problem: ', error.message, clone)
        clone.text() // 5
        .then(function (bodyText) {
            console.log('Received the following instead of valid JSON:', bodyText); // 6
        });
    }) 
})

// Round time to next increment if needed then send back to index to store in database
function progressTime(data)
{
    while(data.currentSeconds >= 60)
    {
        data.currentMinutes += 1;
        data.currentSeconds -= 60;
    }
    while(data.currentMinutes >= 60)
    {
        data.currentHours += 1;
        data.currentMinutes -= 60;
    }
    while(data.currentHours >= 24)
    {
        data.currentHours -= 24;
    }
    // if($_SERVER['HTTP_HOST'] == 'localhost')
    // {
    //     let campaignIdURL = "/TimeTracker/campaign/index.php?action=setCampaignTime&seconds=" 
    //     + data.currentSeconds + "&minutes=" + data.currentMinutes + "&hours=" + data.currentHours + "&name=" + data.campaignName; 
    // }
    // else
    // {
        let campaignIdURL = "/campaign/index.php?action=setCampaignTime&seconds=" 
        + data.currentSeconds + "&minutes=" + data.currentMinutes + "&hours=" + data.currentHours + "&name=" + data.campaignName; 
    //} 
    fetch(campaignIdURL)
}

// Progress the time for all duration trackers
function progressTrackers(campaignId, time)
{
    console.log("here");
    // if($_SERVER['HTTP_HOST'] == 'localhost')
    // {
    //     let trackerURL = "/TimeTracker/campaign/index.php?action=getTrackerInfo&campaignId=" + campaignId; 
    // }
    // else
    // {
        let trackerURL = "/campaign/index.php?action=getTrackerInfo&campaignId=" + campaignId; 
    //} 
    fetch(trackerURL)
    .then(function (response) 
    { 
        clone = response.clone();
        if (response.ok)
        { 
            return response.json(); 
        } 
        throw Error("Network response was not OK"); 
    }) 
    .then(function (data) 
    {    
        console.log(data); 
        if(time == 'secs')
        {
            // Iterate over all trackers in the array
            data.forEach(function (tracker)
            { 
                console.log(tracker.trackerName);
                tracker.remainingSeconds -= 6;
                if(tracker.remainingSeconds < 0)
                {
                    tracker.remainingMinutes -= 1;
                    if(tracker.remainingMinutes < 0)
                    {
                        tracker.remainingHours -= 1;
                        if(tracker.remainingHours < 0)
                        {
                            // if($_SERVER['HTTP_HOST'] == 'localhost')
                            // {
                            //     let deleteTrackerURL = "/TimeTracker/campaign/index.php?action=deleteTracker&trackerId=" + tracker.trackerId;
                            // }
                            // else
                            // {
                                let deleteTrackerURL = "/campaign/index.php?action=deleteTracker&trackerId=" + tracker.trackerId;
                            //} 
                            fetch(deleteTrackerURL);
                        }
                        else
                        {
                            tracker.remainingMinutes += 60;
                            tracker.remainingSeconds += 60;
                        }
                    }
                    else
                    {
                        tracker.remainingSeconds += 60;
                    }
                }
                // if($_SERVER['HTTP_HOST'] == 'localhost')
                // {
                //     let updateTrackerURL = "/TimeTracker/campaign/index.php?action=updateTracker&trackerId="
                //     + tracker.trackerId + "&seconds=" + tracker.remainingSeconds + "&minutes=" 
                //     + tracker.remainingMinutes + "&hours=" + tracker.remainingHours;
                // }
                // else
                // {
                    let updateTrackerURL = "/campaign/index.php?action=updateTracker&trackerId="
                    + tracker.trackerId + "&seconds=" + tracker.remainingSeconds + "&minutes=" 
                    + tracker.remainingMinutes + "&hours=" + tracker.remainingHours;
                //}
                fetch(updateTrackerURL);
            })
        }
        else if(time == 'min')
        {
            // Iterate over all trackers in the array
            data.forEach(function (tracker)
            { 
                console.log(tracker.trackerName); 
                tracker.remainingMinutes -= 1;
                if(tracker.remainingMinutes < 0)
                {
                    tracker.remainingHours -= 1;
                    if(tracker.remainingHours < 0)
                    {
                        // if($_SERVER['HTTP_HOST'] == 'localhost')
                        // {
                        //     let deleteTrackerURL = "/TimeTracker/campaign/index.php?action=deleteTracker&trackerId=" + tracker.trackerId; 
                        // }
                        // else
                        // {
                            let deleteTrackerURL = "/campaign/index.php?action=deleteTracker&trackerId=" + tracker.trackerId; 
                        //}
                        fetch(deleteTrackerURL);
                    }
                    else
                    {
                        tracker.remainingMinutes += 60;
                    }
                }
                // if($_SERVER['HTTP_HOST'] == 'localhost')
                // {
                //     let updateTrackerURL = "/TimeTracker/campaign/index.php?action=updateTracker&trackerId="
                //     + tracker.trackerId + "&seconds=" + tracker.remainingSeconds + "&minutes=" 
                //     + tracker.remainingMinutes + "&hours=" + tracker.remainingHours;
                // }
                // else
                // {
                    let updateTrackerURL = "/campaign/index.php?action=updateTracker&trackerId="
                    + tracker.trackerId + "&seconds=" + tracker.remainingSeconds + "&minutes=" 
                    + tracker.remainingMinutes + "&hours=" + tracker.remainingHours;
                //}
                fetch(updateTrackerURL);
            })
        }
        else if(time == 'mins')
        {
            // Iterate over all trackers in the array
            data.forEach(function (tracker)
            { 
                console.log(tracker.trackerName); 
                tracker.remainingMinutes -= 30;
                if(tracker.remainingMinutes < 0)
                {
                    tracker.remainingHours -= 1;
                    if(tracker.remainingHours < 0)
                    {
                        // if($_SERVER['HTTP_HOST'] == 'localhost')
                        // {
                        //     let deleteTrackerURL = "/TimeTracker/campaign/index.php?action=deleteTracker&trackerId=" + tracker.trackerId; 
                        // }
                        // else
                        // {
                            let deleteTrackerURL = "/campaign/index.php?action=deleteTracker&trackerId=" + tracker.trackerId; 
                        //}
                        fetch(deleteTrackerURL);
                    }
                    else
                    {
                        tracker.remainingMinutes += 60;
                    }
                }
                // if($_SERVER['HTTP_HOST'] == 'localhost')
                // {
                //     let updateTrackerURL = "/TimeTracker/campaign/index.php?action=updateTracker&trackerId="
                //     + tracker.trackerId + "&seconds=" + tracker.remainingSeconds + "&minutes=" 
                //     + tracker.remainingMinutes + "&hours=" + tracker.remainingHours;
                // }
                // else
                // {
                    let updateTrackerURL = "/campaign/index.php?action=updateTracker&trackerId="
                    + tracker.trackerId + "&seconds=" + tracker.remainingSeconds + "&minutes=" 
                    + tracker.remainingMinutes + "&hours=" + tracker.remainingHours;
                //}
                fetch(updateTrackerURL);
            })
        }
        else if(time == 'hour')
        {
            // Iterate over all trackers in the array
            data.forEach(function (tracker)
            { 
                console.log(tracker.trackerName); 
                tracker.remainingHours -= 1;
                if(tracker.remainingHours < 0)
                {
                    // if($_SERVER['HTTP_HOST'] == 'localhost')
                    // {
                    //     let deleteTrackerURL = "/TimeTracker/campaign/index.php?action=deleteTracker&trackerId=" + tracker.trackerId; 
                    // }
                    // else
                    // {
                        let deleteTrackerURL = "/campaign/index.php?action=deleteTracker&trackerId=" + tracker.trackerId; 
                    //}
                    fetch(deleteTrackerURL);
                }
                // if($_SERVER['HTTP_HOST'] == 'localhost')
                // {
                //     let updateTrackerURL = "/TimeTracker/campaign/index.php?action=updateTracker&trackerId="
                //     + tracker.trackerId + "&seconds=" + tracker.remainingSeconds + "&minutes=" 
                //     + tracker.remainingMinutes + "&hours=" + tracker.remainingHours;
                // }
                // else
                // {
                    let updateTrackerURL = "/campaign/index.php?action=updateTracker&trackerId="
                    + tracker.trackerId + "&seconds=" + tracker.remainingSeconds + "&minutes=" 
                    + tracker.remainingMinutes + "&hours=" + tracker.remainingHours;
                //}
                fetch(updateTrackerURL);
            })
        }
        else if(time == 'hours')
        {
            // Iterate over all trackers in the array
            data.forEach(function (tracker)
            { 
                console.log(tracker.trackerName); 
                tracker.remainingHours -= 8;
                if(tracker.remainingHours < 0)
                {
                    // if($_SERVER['HTTP_HOST'] == 'localhost')
                    // {
                    //     let deleteTrackerURL = "/TimeTracker/campaign/index.php?action=deleteTracker&trackerId=" + tracker.trackerId; 
                    // }
                    // else
                    // {
                        let deleteTrackerURL = "/campaign/index.php?action=deleteTracker&trackerId=" + tracker.trackerId; 
                    //}
                    fetch(deleteTrackerURL);
                }
                // if($_SERVER['HTTP_HOST'] == 'localhost')
                // {
                //     let updateTrackerURL = "/TimeTracker/campaign/index.php?action=updateTracker&trackerId="
                //     + tracker.trackerId + "&seconds=" + tracker.remainingSeconds + "&minutes=" 
                //     + tracker.remainingMinutes + "&hours=" + tracker.remainingHours;
                // }
                // else
                // {
                    let updateTrackerURL = "/campaign/index.php?action=updateTracker&trackerId="
                    + tracker.trackerId + "&seconds=" + tracker.remainingSeconds + "&minutes=" 
                    + tracker.remainingMinutes + "&hours=" + tracker.remainingHours;
                //}
                fetch(updateTrackerURL);
            })
        }
    }) 
    .catch(function (error) 
    { 
        console.log('There was a problem: ', error.message, clone)
        clone.text() // 5
        .then(function (bodyText) {
            console.log('Received the following instead of valid JSON:', bodyText); // 6
        });
    }) 
}