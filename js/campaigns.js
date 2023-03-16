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
    let campaignIdURL = "/TimeTracker/campaign/index.php?action=getCampaignInfo&campaignId=" + campaignId.value; 
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
    let campaignIdURL = "/TimeTracker/campaign/index.php?action=setCampaignTime&seconds=" 
    + data.currentSeconds + "&minutes=" + data.currentMinutes + "&hours=" + data.currentHours + "&name=" + data.campaignName; 
    fetch(campaignIdURL)
}
