{literal}
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.colVis.min.js"></script>

<script type="text/javascript" src="custom/include/generic/javascript/toastr/toastr.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<link href="custom/include/generic/css/jquery.dataTables.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/toastr.css" rel="stylesheet" type="text/css">
<link href="modules/CT_Activity/css/project-details.css" rel="stylesheet" type="text/css">
<link href="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.css" rel="stylesheet" type="text/css">

    <div class="col-lg-3 col-md-6 col-xs-6">
        <div class="form-group">
            <label for="activity_from">Select a Project</label><br/>
            <span class="project-select">
                <div class="row">
                  <div style="width: 100%">
                    {/literal}
                    {html_options name="selected_project" id="selected_project" selected=$actual_project options=$project_list}
                    {literal}
                  </div>
                </div>
            </span>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-xs-6">
        <div class="form-group">
            <label for="activity_from">From Date</label><br/>
            <span class="dateTime">
                <div class="row">
                  <div class="col-md-9 col-xs-9">
                    <input class="date_input" pattern="[0-9\-]+" autocomplete="off" type="text" name="activity_from" id="activity_from"
                           style="width: 100%; height: 34px" maxlength="10" value="{/literal}{$activity_from}{literal}"/>
                  </div>
                  <div class="col-md-3 col-xs-3">
                    <button type="button" id="activity_from_on_create_trigger" class="btn btn-danger" style="float: right"
                            onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
                  </div>
                </div>
            </span>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-xs-6">
        <div class="form-group">
            <label for="activity_to">To Date</label><br/>
            <span class="select">
                <div class="row">
                  <div class="col-md-9 col-xs-9">
                    <input class="date_input" pattern="[0-9\-]+" autocomplete="off" type="text" name="activity_to" id="activity_to"
                           style="width: 100%; height: 34px" maxlength="10" value="{/literal}{$activity_to}{literal}"/>
                  </div>
                  <div class="col-md-3 col-xs-3">
                    <button type="button" id="activity_to_on_create_trigger" class="btn btn-danger" style="float: right"
                            onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
                  </div>
                </div>
            </span>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-xs-6">
        <div class="form-group">
            <label for="search_action"></label><br/>
            <span class="dateTime">
                <div class="row">
                  <div class="col-md-2 col-xs-2" style="padding: 5px;">
                      <input id="search_action" type="button" name="Search" value="Search" class="button">
                  </div>
                </div>
            </span>
        </div>
    </div>
    <br>
    <div id="resultsContainer">
        <div id="generalInfo" class="clear"></div>
        <div id="projectGeneralContainer" class="row">
            {/literal}
            <div class="col-sm-4 col-xs-12"><div class="container-fluid ratingItemContainer boxRating">
                <p>Assigned Resources: <span>{$AssignedResources}</span></p>
            </div></div>
            <div class="col-sm-4 col-xs-12"><div class="container-fluid ratingItemContainer boxRating">
                <p>Total Activities: <span>{$TotalActivities}</span></p>
            </div></div>
            <div class="col-sm-4 col-xs-12"><div class="container-fluid ratingItemContainer boxRating">
                <p>Total Time: <span>{$TotalTime}</span></p>
            </div></div>
            {literal}
        </div>
        <div id="graphInfo" class="clear"></div>
        <div id="projectGraphContainer" class="row">
            <div class="col-sm-4 col-xs-12"><div class="container-fluid graphItemContainer">
                <span class="chartDetails">Billable vs. Non-Billable</span>
                <canvas id="pie-chart-billable"></canvas>
            </div></div>
            <div class="col-sm-4 col-xs-12"><div class="container-fluid graphItemContainer">
                <span class="chartDetails">Invoiced vs. Pending</span>
                <canvas id="pie-chart-invoiced"></canvas>
            </div></div>
            <div class="col-sm-4 col-xs-12"><div class="container-fluid graphItemContainer">
                <span class="chartDetails">Worktypes</span>
                <canvas id="pie-chart-worktypes"></canvas>
            </div></div>
        </div>
        {/literal}
        <div class="clear"></div>
        <div id="dataContainer">
            {if $userHasRateAccess == 'true'}
            <div id="mainRateDiv" class="col-lg-12 col-xs-12">
                <span class="tabTitle">Worktype Details</span>
                <table id="dataTableRateDetails" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>WorkType Name</th>
                        <th>Billable Time</th>
                        <th>Non Billable</th>
                        <th>Invoiced Time</th>
                        <th>Pending</th>
                        <th>Total Hours</th>
                        <th>Rate</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="clear"></div>
            <div class="clear"></div>
            {/if}
            <div id="mainEmployeeDiv" class="col-lg-12 col-xs-12">
                <span class="tabTitle">Resource Details</span>
                <table id="dataTableResourceDetails" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>WorkTypes</th>
                        <th>Billable Time</th>
                        <th>Invoiced Time</th>
                        <th>Non Billable</th>
                        <th>Pending</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="clear"></div>
            <div class="clear"></div>
            <div id="mainActivityDiv" class="col-lg-12 col-xs-12 hiddenTable">
                <span id="employeeActivityDetailsTitle" class="tabTitle">Activity Details -</span>
                <table id="dataTableActivityDetails" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Score</th>
                        <th>Time</th>
                        <th>Billable</th>
                        <th>Work Type</th>
                        <th>Module</th>
                        <th>Pending</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <div id="noData" class="empty-state" style="display: none;">
        <div class="empty-state__content">
            <div class="empty-state__icon">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAN0AAACuCAMAAACFrPhHAAAC9FBMVEUAAADn6do3tE7l5ufo6ejl7vLs8fPd6e3woX/o2WoutEz/0Ug1Lj7e3t3s7e0e1VAX1lL/41Hl5eUQykrj5eWm8l+o5Vzs7Ow6NUcM0FAM21dG41Q6OT2O7lz135YEtz8B2Vgm31EJ21jh5OTnw3A4MUTE92IBuEM44lMojErg4OACuUKI7VsBsTM/41Sm81/i4+PF+GLg4+PU82OG7VvW9mFs6Vjq7Oz411rNmoCin6bj4dbz3pP643zR0tHk/KG5uLvn6e7////8/fzp+v/u/P/4/v/z/f/k+f/k5OT/Yhu79mHZ2dmR8Fzc3d3G+GKi8l7r6+yp9F/f39+a8V1/7VpM5VV261n/301Z51b/2ktk6VjA+GHQ+mOw9V+19mCI71v/4lDm5ubi4uFVT2Xw8PDK+WL09PL/wkH/1Un/uz474lP/yUX/WQ3/cltu61n/0EbW1tYA21rU+2QByVJOR1/S09PFxcZfVmkA1FcA4lz/bWD/dlfZ/GX/kSsBrTD9yldEPVX/Z2X/tTv+qzn/9eQ2L0UBwk8Aszr/448Bz1T/4oMAt0L/Ymr/z14r4FH/mTL/iSpJQlr/ojY+OFD/sjL/gSn/rCfqrDba4+b/76v/7KP/6JkEvE3Kysr/oiC4t7f/1Gr/230OnD3BwsL/mSb8w07/2HP942H/yj04MzL5+fm8vLxLw1uJiYn/oiz/uzL7/PHegVYNqEjPz82rqKeRkZH/eCQRn0fuvEN/f3+nsbbf1M3/wTn/bh4uKCn/3kToUj35VmFbylwLskz9hF+R515JPzTttDsVj0J2dHFqaWmf6V+xsK+U0lGgoKDvYEml11KCzE5gwk3/7t5IuUr+5eTo3trYnjfumzTyfi3f32FpVjbyaSP/cnKwur/kh1xl2Fs6u1orsUbb+pLt7Vnu47Qjl0nDlTlfXFvwWFCrhDnv9/Tz78xyx06YdznW+mq53lSCZzfktUOwlEnR5pfgs1hRUFBDmUTTwq79m57P8XpxWfaWAAAAQnRSTlMABAR+/vlX/vMRGeo/imoyY1GkSS1OLYq0hquPb6L5qurl1Ej93IbwwvfhyG7r4ta72pn12q/QxKz72smTYeGxsKmDcPDbAAAXwklEQVR42tyXv4vTUADHk1iVVrT1RwtaWjgPpIhUxZ8gCl6nYI3Wtja29yNNUZAbDjno1tGpw811dLFLBneHwIGTe+cUzOb/4DeNyUvbF70+n5D4uR4luRLeh8/70ROOhOSwcC38H/gmUiqVSuM3+es6/oauQSqdyZdK5VcOnU7n7t3CtWxOirmgNBNI50vllw6uHNjb22u1WrcL2VR8BWfDTufLu7u7y3KgCQrXkvH0w5BTmdLBrisHKHKapt0u5OLn57jl1+BGylHlnoJCVhDipIcW0sW1AyIXUs61azQKuRj5YaAZuB2tXMPheSEZFz1JSF2CW3g5Igdmcs+fX4/H9PTC0cvR5cCLF3HI56y4g1XLQQ5czwmiEGkwK0sHLOXA9nY22vUkIb12lHJgQQ5u2zs716Jcj8itUo7I7Ty5El09WrmjygHIPYmu3t+XA5sR1cOGwliOyMFuM5JrT5Kky+zliNzms2wE9SThEo9ysNuM3rknCRk+5cBNMWJ22FF4yLl2byK2s2DRrfGTe/PmD0tPDEUg8Ex3kbLmmOW2biYZx8lVj8xLnuW2trZ+PzfXz9A5keSqR/ZLDuWeeXLgVui+KYrJe7XXISTWuetB7jzXcmD/TqidcKyWqFHB/asifzupxLUc5Pb3ES/E7oYs3zhHpSfLF/5JOno5drmFeGKAG/X6ukDlal25IAbgY1fitVsSuQHihXCu3w+xe9TvJ7mnS3MvNxjsD+a2zfUThAfV6ukTVK5W350hVzx2UNjlqeVaTeZyA4dbSdHnTI2FezwOe6lMK2cZh3BjK+cKZgWfxwk9gZf7E4Luvfs/Mo905ynlWofTQ2O6xyQHte8Od876PEjoOhn18eM6Hfwl8Dm99/DsAiLDxKSU60xbTc0yNE+usUq5790FVPuk7KP/KNoyFd0u2nrgcjLuLnAqxzAxl+RaltHEvJzCcGrQ+PpkJ1xOpdj1ZJeePOma5hDvC+COrZpmMfBJ2C1yXxBX3DFpu2Vn2nTMUG42N7+6NBrfHJBwO7Qczc60Rz2PUdHEnUlvmdHQ7KrjHmEyVpfiJVf9r5V2zjWNqWVNLU2jrjh0C5OD3nd1RhdDc19oN6p7jGxTNcd1Co63+XFEbrh23nNmrGyXp59zlmG0ghvKvF+4HOzaqoNJQDul7qIo9WJ3bNdxPQ/uKB+7n74oin89CT6DxQ6U6N9QNI3lKBj4du3xkGArQapKGNX5q2EANrtUmf4NBWawY5Bz7NqqOaxsEBLvqgwowUd8MNV2u72qXZpWDnKtQ2tZDvxRDnZt2H3Y8MZWqWzojHYV/xkbn9U2g915upzlnOZaY3nNgVA5zw64dp6h3H/HQLUyE3Of8r7NYpehTkscdY2GZUBtTsz/VjkvBzfg273FMN5+qDggnDO2Wp+Fn7Tby2sTURQGcC3ZmE0KohDFB0F0oSDi4w+YAUG0mmhEkBofRdOKiI6zmaggPggMuhTczFaEkEUZFyO4sR2DQheuVBCEFkEQBDe60I3fnTszJ9Ocm6Tt7XfNTCJd+OPce+50Jp4yKCvQZXHIGGCYl+2x8ny7O7Nx2nNXxmGjypGNZmYa8OqXlxEzq5tS65RPtTezv/KcWaiOlU+3y9VOmrnOHOXoiRj3pTf3niOTWz/GmTY0ROqmpqBTfimK1fG/z7VxKbZwplzNrDf6ZUfi9v95e+fO9TS3k9xP8gT5+vejYWrRIawOrpE8z9useLIqdvMyu4kjMe7t9TsIAYmY4pCZJzp4j6YUOqjyxdL20ijH26z6HgpkA/Y54GS6ZInutuTdJ55uHc3K9aWGSJHhbeZwyGDcjgRHuutShhcVT/D+6tDdwOjRAVRsxFlPPNKxuPLY/OkBVyhvGR2GBGZ1X1uGBh1COvrmUCPJpl7dHh53Brt5uwpaqqNFh4itAB1FpQNO78ozeR1m5fZGXx07LccWOrXa/GztJFyxCSIkvi7BaYCObMBp1tGSG41hts3rNrBrbn72ZLVcbZdrp9uzXGq31Lr7XcWTupkZDbob17I6UEaSWWkFFt9WNjK4KjbyWrnWaddqcx3KHGUcJeR1zMTUp7sGHbfkQk8cmT0hf6YHh5xsz1Y7C6fPnzxPGxxCtxdwUuuQ1dAh0KW4QinBOQHfM5FD/E1ZNBXgehsKLb6+6261dcBRP/Ht6FRYrMPnLQwOQUMBrt8dZ+W6Wx0dbKRDs7QaYjTwsv3ozfY8o9ujvJ3eH3dXWbvV16FZRjD8wStwImiJbKTbuKzb6VztJI10iH5dgrMs20KgC0MYEdoQun0HlveUh6kd21P06ySuEbhChZYiaHhbZHRYeEvHQfflzwdFfsiRZEaXbvLa5CR0MQ418yKdj1OUUVa3YRmVg+7zm+Hy48nMDz06BLoYJ5sJjgGOUahldmddZ+kPQu4ib/l87Qlqp08nuqWMEzg4Bnasky2TmZoXloH79VLmNZtPrz9lUmkZmnTAJfHRVzwUUKYkMFzXXE7lfuOmVXJnDjmORDfJJ05NTMgHVEgOqYjk6ivWwQbd2sJ2y5FDNEsncC3HcVBI2TK5HFg6DrosD4l9ECIRkYC6dPkSKJb02D6AVvS56bJNRfaVIXGwCRrpJC+lEQ6yRFfRpbs6OXn1VckRseTBtwPI7NAPfFc2Fda3l8f9y14/Q5etHZIpXYZHtdMwMw2hQ15FrNC25Pm914QsbDpoKtLGFo/Djf/+PX4lqdsviqzdv5exjWpHOiSy0cxs6ahdqgulznvv+5iTlviEZafijeztwY3/nM5NTPeL2TctEdPQFDOpnStYtg8PEjSFTKYInbJ4i3HnvxnG5UuGOvUj/TJRMVsY9fQh+OUV666SDp0yKiGQaWjZcXtepKPKHf1mmpUj+AcqYtRzl+JQB6Ecga4FXfI5V9eocy3fc1zHhVFiEVp2XNbtXbTmXh4/fvOm6hFpxTBa6YOPegvBx2wqUehnjJUFugdS57qu4/mCGFqRVRhp2SnmJuG6aqeMcflYnJu5usiAB5A5fToMO3DEy40SnWi343k7s/sc1l3lCHd1SE+t4iTl6RfD0KBD3sWgwHYCDyxROxt/QReZyr4JXYI7kehUMczpF0vII8PUqvM9L3AQ2xM7uesqJyZdbu5LcaQzlJl+ga+kDBX82KOV1w426Gyp83zfdr0wEFs5MzG5r/RuJNxQuovDR6sOsd+/9yOZIxdfnnTqzpLgButMqt0wJdShewjdM8+1EdcOQs92IcMHHJiJyVRvw7jEDVG7wbqzmnVPhS6MdI4fShkiTszEZHlHkzt7w9ROyDAASYO3XLTUDuPZ96haXoATJdMxR5KwvBNZHT8Oi9pR1c4mA1k9HfLue1PoAi+jK8a4rInhoXMKHOlUgQ4aFjXVPeRBm87H1HTDqHSUAiQpLF9YP1rctntbgeWtOwjcuVR3mB2mKXRcppho0cEGXehDEzQzuN0JLb8erF2PZXYV+P9FtZN0hwWv92XiAB1HWl3ds2bQdH0/LZ3nh+gpI1IGWBwPuMfb1vDZsO/cuW8mdGCIgWotOuOg0t1I0/VWm84OvGYgZc3Qx27u2SXQRrclMgqvQ/n+t3d/MW1VcRzAGeKmhqmJRjFZ9MHNRI2J2Yt/otH0TqCkISF9MXsEmzKCyUi0+AQYmIxgCDj5M/50DGoNBdkUKKPaockSoATI2IzFYDRZ4iCARCD+efP7O+f0nt7elvZein8Wv/cP0GQdn33PObe0MA48FenOYsEhdlab+EA7MgklcdrUpKZTUtSNXMPUE7Jr9WxNeYho+jyYkTCPvMa7E9OMRPSGznzqobtTKg49fa11naQPyynsDJ1ZntSdqzvX8U7PzJUeJuth/SER2siVyDszIzg/i7kYNw8//8zhg4oS0SnCJ0oTc5F0oi8AJ3YmOA8mbBM72xiY5d5ykeSPoh1Wq3NXHXDQ1dfPzGA41gMm0nP2LEf1j3Bbfz9bVHANTPAzZCUldruiXTO1SybNuyZOw0OSiZ2tqcmtk9xGObU2ObW8to1XulVdMlwhnmxSdtchHfXXrjCZzFnkQzr1s3f6+4HF24eAi59n6JuurehOLpTa1ZPOQneSaD7flG+q/PQAtvIBbKeXfbiNgCnqgMO/6GASHbrrwaqiyYcg9RNrpJ+EGJRnKbjAJ/zpxhLiZfE1M7qx6AKhI9wOGAgk5QOIKGtlGTdTNstLKWdSaM5enEJ3t0dYcTKR1lAabHiP5Qkt7oAmx4lnt2WhMfg4TY5O1iB0p7FCvrk5SbTNcOlp2HBwzUBpeI0Bl5PoFOyEQ6y4ju6iO0dZul0fU111NVrjOvhEsKJkavrS5rCdD06FPgFYVJc8uO7kNqPBJQIZ93HgSvLuOI43l1R3jS0k1yLjs6caOq6amamO4J6LXVEejsnznGcZdGVZ0J8QSSfv7jSy7T11Ciqpi8pEB55ApiTS0d07bKQrcQ5qosTTYd710JUOX92x1J+tBqkfBwYlpZrhHsjQNvekFU+Iq89NltgLK2zAFRZaXe7RImB0m0LdsZx8EysJEkR/TBdEe8FgsBQuOhLrCOd2lxQi9BfGvDTmjKNrXrrNLnUojYJxCV21OuGquU8sl1L3aJGqAw7Ll81VYS+029x4tcARGZtK7LyjnNrenDgdJNz05jYhy4LTm+EBjtpcEbqb8aYU5pnNNepmf5ELBWp1RbG6ZjYyYepR0w+dMLEW6SxxsrssVVeCiJ/YsY2Outw0MnXzDjvXlZ/amlzm3S1Pbg0EoRvAxa5jgNrbmpyKdMdwep8y6hodBQ807QtHRcUO4mt0yFLPZ/PzN4St/ko/4VAcziJ6HPGOqzqE+TjONewfJB5sdOTioB1ep5frVrAwrkxPryz7fCswlZUFw7j4rXVMh7dwQSirpdR9p+uOl+cJeCo4j809JAIsUnTdIeEbvyKC9yFYzNaPJMZRHj6ozXFnFuH8ocCC4KkbJh14w9viceQaXe5o9V+jwsrAW5kSt2xBxnShElD0OPdwgHhuuzM2/MKu7+46ixiXM8yGAMnfe07gdO3F5KDF5naNDoeG/cOCJy97VJ3/9wn2iBK8lWXSLK8EKVRWMLzFH6oQjO2zoSLi6XB+P3gYnMAkyZlmqZvvuTG/eAvXAIg0UVdLHS8mB3MdWFDmAn4/4+VqMmgZ+30VOgp4peGVlfBl0C4HL7OuLtfSLdP0ZAEO7KHVYSc4Ohzu3F9sd1uVVHXzyPXFXzA8r/fPPB2DezbJ05qyyoO5SpbVYhka5rwYXO/qKulEBoIkYwniiblWpBbvE04kNDvrH4yLW3BaBrGAGNHBh/z6B6rrj/Y9QbiUdUVWDEcPhqbgSVzlKunKIym9LAIi2YQQKqkLhRYgisJVRnBKCjYloltkAW/++h9/LC5+C19E+FgGcEZ0llzBC4Anca7ZWei2Y3WsvlYZqM5hF7pAaI4gRnFSV9XcXLX0Cwsr8MZ1jM9bYMHH1xPgjOnAGwJvGDwVVziL/B7yxuo6prmuLowDG2Bsx7YwGwKvlyiGcVJXVbV0g0UUiPxibFRKXW6RFRjJc7KP8izFIeBWZ0uaSsv5JnRh3zLOUG351nBuhU7NGfACgVAlYQzipK6KdN9SWIGLrMBbi4u3qp97kEalKR14gWHBA64oMEu8QsVbChpLsBUq6HxrQbxdmeK6umid4p8NIKPEAS4gccZ0394inWzwxvz1X+epOOBM6fJU3jpG6vrwbAg6l8VZA5fQoSiwtnxTm+Hw5pRvua69rr29/ZzMTcugP0Q8mwWpxN0JnFHdLQqEGuIjhDOjy2M+wfM7Bh1+hnNb8j73qroy6ODrWPaxTIXbL126VAddM9uw38Qjt2HGsyuKR+IM666MUKSRhZ51NqUDjzbwSOcZrVwIQTdkycvV6cDbXJ6amtoiHPnONVPYmb5GKBoGzj/kdo9JnGHd0zOU22qephzJzs42qMsjXR5CRGov4K+s9HjcY6HZOQtudnqDqq69lW+tHeHw0iUR6ESgo0/Qirtw013MDXOccd337yFvUd6lfER5n/LxkYxMEzoOBK830OsZ8niGKgMLwOXqddjhg0pNtE7BZovchd8gDjlT1SJ04Gl9TJdjqjtEnD29QzzsBujK4OJbOwIcJZ6uCjpWQIW4izmHQZxCOgQ6LW7POm7DNtRLmcOtQidSWtsuE42rUgMdRRF3UWwMh2h07xGO8Rhuj92J4ekeo8+sd13qeHNlGt0nl7CzTeiahY4i7mLQIE12l3hkGpx3eXczncz60NjY2Nx53Ji4O+jUxHaHOOkuUJ15nZTJ3o7l5ORkm9XJFI66HLhNq9N2B5UacrVgI52IYh+tcFrM6Fq4DrKPAKJ8EIm8HJjWqRcHVVer6i60t18QWzSuhcLO0JmM1LWRjsalBKq6TGxmdTJv4NDrWi9cAIwfOh2L1CmKYlrXxnUIxiUHqrq9d4fodbWka1d9n1SRC5vUpas7ROokz6xOLCBv0IFdtid1OEinBjoEOJzoX5vv6dYBRzrGM69L0p2IVsdpdLS0qUl3d2nRUVfxNjqtQycSrWPLJJ32qlNoFxs+/C69ujeggyNh1r2tBNPpWsRSmdbuBou+iq9D9qLLS7BBhye+aNK1Ml2d11t2iXSA4cmU5hYox8fH8RnhZEKnOBHx7eNZxXb7Vy3j6Zx3sru8RN15G7q6uvrg++TCxe7u7oZGL+nqmi42NdV4wRxvW+ro6FiqGh83rnNyGGT0iht0beP7MDKxJ+iurKmTpcvbfqGvq7uhoaGxoeaTlrpPCVfT5K1qa5uegA4+wzpF6IArxos2eBUFOuRvm3eOGsA66ehub+rsZrq+xtrmTy+SDryy8bqJ6Q6WFlPdieYIZ2O6tjTq3ji0u66BVwdeX3cX1zU2XmziOvLVTUR0S/jq1Wh3RVG4QptNdke4NOiSrJldEV1XJ3Bc19fXx3UUr9AhBnWIQ4MTuvR2B0TibHQJHSJ1nzJdk1a3/blBG3QaXMU+6NDdicQ6Z6eIVidHptcb0f1suDrFocG5CvepuxNxNzqhvATdwRatOwqc0WhxFVrdu2nSnYAiEXBdVJe0uw0zuuJiiXPZoLsK3Q9/17wD/GbX7vMOOpPVQSebQ3W76+4z/rX5CdKdIES8nR+dXSmMzOkzZnRZGhzXXb0aV3cs04TuBOl2j6Mrhe4eB854ijU46GCDTjfvUB2eDtsXHRaWpPNuQgHOhM4u5hxwMTpEozuyX7r15CPzZxPVKZbBqOaS6bL3S4fyknR31GIqWRyHJNXRtNsXHZLsscqG0xGJM+U4rLw3V5QONv28EwPTnC45Lv8MlafvLqI7WumJn8qYuDUZxXdxIQl0sjtxPTCryxdbzEnenL9bd9PbUOiSFEZxsSQdmWaqI10+6QRHD5S3OxLPO1ad3BLhaI8DlDyuK9DraNahOtO6fL5rTvJG9tGGTqdOu9+kKJFMr5IyAaOc//NqgX7e8QXTlC6fdLtFcNc743cH3Z8eliHPkEwvbalG/rGxgoKCqwWa7j76WF7J062T2ejW6fjAPAqZIPHMxWZsbizVFFC0IxO4TOD2U4d0dscdmd5KxMPzoy5fGstLWh3hzE060h0wosvS6ZoIt/STLl+Yzg+x3VFz9M1hprp7UqyZSZKPDeXF626i4O04KcCu2VJMgaqTuEzgTOmePH7oUKHtUEo5f8gRb1VpKUh7ZHcff3DsSIbZZD55/Px5W8X51HIof6NB111dmmFS995bWC2NjcoDmohVJT/l6EfmeME+5BvSvYvvcMhGA3v4xZUP351vICgvRtf89j7kh9V3Pzp2LCfb8MPmR2Pzwt2G0hWju2cf8tKC/+WXXz98112HE+X++EvkvUW6ZMWkWN2jY7UWW+m0odF5H8+/22gOJU8WYrXLX6qjyzMp6fTUpPk5SldzEb8jzBo/JanErkkhh3AN0Yzp4LtrrzkcrXvlrn8mGJn7lFek7sWMf1kO7Dn3Pt7Yh9e4SPfIgX8qGfsTzN1XGxuP7uz89unFF/9dv0U6PbzMx3cmkc2j92bcecH/2eWb9PkmJ+mZjjsvmRk51N2xO9FGuuzJyTu1uv91/+H8r/sv5z7SZd+husyMIz5fzp1p4+3dl/HvyV/H06x7VvcVEwAAAABJRU5ErkJggg==" alt="">
            </div>
            <div class="empty-state__message">No records has been added yet.</div>
            <div class="empty-state__help">
                Records are related to employee activity reports.
            </div>
        </div>
    </div>
{literal}
    <script type="text/javascript">

        toastr.options = {
            "positionClass": "toast-bottom-right",
        }

        const base_activity_title = "Activity Details - ";

        const bar_options = {
            indexAxis: 'y',
            elements: { bar: { borderWidth: 2 } },
            responsive: true,
            legend: {
                display: false
            },
        };

        const base_data = {/literal}{$base_data};{literal}
        console.log(base_data);
        let selected_employee = null;
        let employee_data = [];

        function ConfirmDialog(title, message, action,e ) {
            $('<div></div>').appendTo('body')
                .html('<div><h5>' + message + '</h5></div>')
                .dialog({
                    modal: true,
                    title: title,
                    zIndex: 10000,
                    autoOpen: true,
                    width: 'auto',
                    resizable: false,
                    buttons: {
                        Yes: function() {
                            action();
                            $(this).dialog("close");
                        },
                        No: function() {
                            $(this).dialog("close");
                            $(e.currentTarget).attr('disabled', false);
                            $(e.currentTarget).css("background-color", "");
                        }
                    },
                    close: function(event, ui) {
                        $(this).remove();
                    }
                });
        }

        function synchronizationAction(moduleAction, url){
            const selected_project = $('#selected_project').val();
            const employees_ids = (Object.keys(base_data?.employee_data))
            if(employees_ids.length===0){
                toastr.error("Empty Employee List", 'The list of employees is empty');
                return;
            }
            $.ajax({
                type: "POST",
                url: 'index.php?entryPoint=CTActivityEntryPoint',
                dataType: 'json',
                data: {
                    action: "synchronizationAction",
                    employees_ids: employees_ids,
                    project_id: selected_project,
                }
            }).done(function (data){
                toastr.success("Synchronization Started", 'you will receive a notification when it is finished.');
            });
        }

        function updateActivityDetails(employee_id){
            $('#mainActivityDiv').removeClass("hiddenTable");
            selected_employee = employee_id;
            employee_data = base_data?.employee_data[employee_id];
            $('#employeeActivityDetailsTitle').text(base_activity_title+employee_data?.name);
            $('#dataTableActivityDetails').DataTable().ajax.reload();
        }

        $(document).ready(function(){
            $('#MassAssign_SecurityGroups').hide();

            if(base_data?.employee_data?.length===0){
                $('#resultsContainer').hide();
                $('#noData').show();
            }

            const ctx_billable = $("#pie-chart-billable").get(0).getContext("2d");
            const ctx_invoiced = $("#pie-chart-invoiced").get(0).getContext("2d");
            const ctx_worktype = $("#pie-chart-worktypes").get(0).getContext("2d");

            const isValidDate = (dateString) => {
                const regEx = /^\d{4}-\d{2}-\d{2}$/;
                if(!dateString.match(regEx)) return false;  // Invalid format
                const d = new Date(dateString);
                const dNum = d.getTime();
                if(!dNum && dNum !== 0) return false; // NaN value, Invalid date
                return d.toISOString().slice(0,10) === dateString;
            }

            const validateInput = (e) =>{
                if(!isValidDate($(e).val())) {
                    $(e).css('border', 'solid 2px red');
                    $('#search_action').prop('disabled', true)
                    return false;
                } else {
                    $(e).css('border', '1px solid #090a0a');
                    $('#search_action').prop('disabled', false)
                    return true;
                }
            }

            const validateIt = () =>{
                setTimeout(function () {
                    $('.date_input').each(function() {
                        let result = validateInput("#"+this.id);
                        if(!result) {
                            return false;
                        }
                    });
                },100);
            }

            Calendar.setup ({
                inputField : "activity_from",
                ifFormat : "%Y-%m-%d",
                daFormat : "%Y-%m-%d",
                button : "activity_from_on_create_trigger",
                singleClick : true,
                dateStr : "",
                startWeekday: 0,
                step : 1,
                weekNumbers:false
            });

            const inputElementFrom = document.getElementById('activity_from');
            YAHOO.util.Event._addListener(inputElementFrom, "change",  validateIt );

            Calendar.setup ({
                inputField : "activity_to",
                ifFormat : "%Y-%m-%d",
                daFormat : "%Y-%m-%d",
                button : "activity_to_on_create_trigger",
                singleClick : true,
                dateStr : "",
                startWeekday: 0,
                step : 1,
                weekNumbers:false
            });

            const inputElementTo = document.getElementById('activity_to');
            YAHOO.util.Event._addListener(inputElementTo, "change", validateIt);

            $('.date_input').keydown(function(e) {
                if ( e.keyCode >= 37 && e.keyCode <= 40 ||
                    e.keyCode >= 46 && e.keyCode <= 57 || e.keyCode == 189 ||  e.keyCode == 8 ||  e.keyCode == 9) {
                    return true;
                } else {
                    return false;
                }
            });

            $('.date_input').focusout(function() {
                validateInput("#"+this.id)
            }).trigger("focusout");

            function updateDatesRange(){
                $.cookie('careers_activity_from', $("#activity_from").val());
                $.cookie('careers_activity_to', $("#activity_to").val());
            }

            updateDatesRange();

            $("#search_action").click(function(){
                const selected_project = $('#selected_project').val();
                updateDatesRange();
                if(selected_project !== undefined){
                    window.location = 'index.php?module=CT_Activity&action=index&parentTab=All&record='+selected_project
                }
            });

            const data_billable = {
                labels: [ "Billable","Non-Billable" ],
                datasets: [{
                    data: [base_data?.total_billable_time, base_data?.total_non_billable_time],
                    backgroundColor: [ "#36A2EB", "#FF6384", "#FFCE56" ],
                    hoverBackgroundColor: [ "#30A0E0", "#F06080", "#F0C050" ]
                }]
            };

            const chartBillable = new Chart(ctx_billable,{
                type: 'pie',
                data: data_billable,
                options:  bar_options
            });

            const data_invoiced = {
                labels: [ "Invoiced", "Pending" ],
                datasets: [
                    {
                        data: [base_data?.total_invoiced_time, base_data?.total_pending_time ],
                        backgroundColor: [ "#36A2EB", "#FF6384", "#FFCE56" ],
                        hoverBackgroundColor: [ "#30A0E0", "#F06080", "#F0C050" ]
                    }]
            };
            
            const chartInvoiced = new Chart(ctx_invoiced,{
                type: 'pie',
                data: data_invoiced,
                options:  bar_options
            });

            const related_worktypes = base_data?.related_worktypes;
            const related_worktypes_base = Object.entries(related_worktypes).map(([k,v]) => { return {
                name: v.name,
                total: v.total
            }});

            const related_worktypes_sort = related_worktypes_base.sort((a, b) => (parseInt(a.total) > parseInt(b.total)) ? 1 : -1);
            const related_worktypes_labels = related_worktypes_sort.map((v) => v.name );
            const related_worktypes_values = related_worktypes_sort.map((v) => v.total );

            const stringColor = (str) => {
                let hash = 0;
                for (let i = 0; i < str.length; i++) {
                    hash = str.charCodeAt(i) + ((hash << 5) - hash);
                }
                let color = '#';
                for (let i = 0; i < 3; i++) {
                    let value = (hash >> (i * 8)) & 0xFF;
                    color += ('00' + value.toString(16)).substr(-2);
                }
                return color;
            }

            const colorArray = [];
            for(var i = 0; i < related_worktypes_labels.length; i++){
                colorArray.push(stringColor(related_worktypes_labels[i]))
            }

            const data = {
                labels: related_worktypes_labels,
                datasets: [
                    {
                        data: related_worktypes_values,
                        backgroundColor: colorArray,
                        hoverBackgroundColor: colorArray
                    }]
            };


            const chartWorktype = new Chart(ctx_worktype,{
                type: 'horizontalBar',
                data: data,
                options: {
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            position: 'left'
                        }]
                    },
                    legend: {
                        display: false
                    },
                }
            });

            const renderLink = (moduleId,record,data) => {
                const link = "index.php?module=" + moduleId + "&offset=1&return_module=" + moduleId + "&action=DetailView&record=" + record + "";
                return `<a href="${link}" style="cursor: pointer;">${data}</a>`;
            }

            const checkBoxRender = (data, type, rowid) =>{
                const checkedStr = (data == 1)?'checked="checked"':'';
                return (type==='export')?data:`<div style="display: flex;"><input disabled="disabled" type='checkbox' ${checkedStr} value='${rowid}'></div>`;
            }

            const viewDetails = (row) => {
                return  `<i class="suitepicon suitepicon-action-list-maps"  style="cursor: pointer;" onclick="updateActivityDetails('${row?.id}')"></i>`;
            }

            const workTypeRender = (data,row) => {
                return base_data.related_worktypes[data].name;
            }

            const moduleRender = (data,row) => {
                return base_data.related_modules[data].name;
            }

            const getStringProjectName = () => {
                const projectName = $('#selected_project option:selected').text();
                return projectName
            }

            const getStringRange = () => {
                const activity_from = $('#activity_from').val();
                const activity_to = $('#activity_to').val();
                return ' from: '+activity_from+' to: '+activity_to
            }

            const getEmployeeTitle = () => {
                return base_activity_title+employee_data?.name;
            }

            const imageData = 'data:image/png;base64,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eHQ9Imh0dHA6Ly9pcHRjLm9yZy9zdGQvSXB0YzR4bXBFeHQvMjAwOC0wMi0yOS8iCiAgICB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIKICAgIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiCiAgICB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIKICAgIHhtbG5zOnBsdXM9Imh0dHA6Ly9ucy51c2VwbHVzLm9yZy9sZGYveG1wLzEuMC8iCiAgICB4bWxuczpHSU1QPSJodHRwOi8vd3d3LmdpbXAub3JnL3htcC8iCiAgICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgICB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iCiAgIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NTVBNzRGN0EzODJGMTFFREJBMkJDM0Y4QjU0MzZBRUUiCiAgIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6Yjk2MDhjMTUtNjFkNy00MDdjLWFkYjYtMjNmODBiMWYyOTM0IgogICB4bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ9InhtcC5kaWQ6NGMwNmRiMTUtOTJlOS00ZTZjLTkxMTMtNGQ4ZjU1Yzg1MGJiIgogICBHSU1QOkFQST0iMi4wIgogICBHSU1QOlBsYXRmb3JtPSJMaW51eCIKICAgR0lNUDpUaW1lU3RhbXA9IjE2Nzg4MjI2ODkxMTE2MTEiCiAgIEdJTVA6VmVyc2lvbj0iMi4xMC4xOCIKICAgZGM6Rm9ybWF0PSJpbWFnZS9wbmciCiAgIHhtcDpDcmVhdG9yVG9vbD0iR0lNUCAyLjEwIj4KICAgPGlwdGNFeHQ6TG9jYXRpb25DcmVhdGVkPgogICAgPHJkZjpCYWcvPgogICA8L2lwdGNFeHQ6TG9jYXRpb25DcmVhdGVkPgogICA8aXB0Y0V4dDpMb2NhdGlvblNob3duPgogICAgPHJkZjpCYWcvPgogICA8L2lwdGNFeHQ6TG9jYXRpb25TaG93bj4KICAgPGlwdGNFeHQ6QXJ0d29ya09yT2JqZWN0PgogICAgPHJkZjpCYWcvPgogICA8L2lwdGNFeHQ6QXJ0d29ya09yT2JqZWN0PgogICA8aXB0Y0V4dDpSZWdpc3RyeUlkPgogICAgPHJkZjpCYWcvPgogICA8L2lwdGNFeHQ6UmVnaXN0cnlJZD4KICAgPHhtcE1NOkhpc3Rvcnk+CiAgICA8cmRmOlNlcT4KICAgICA8cmRmOmxpCiAgICAgIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiCiAgICAgIHN0RXZ0OmNoYW5nZWQ9Ii8iCiAgICAgIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6MjQ5YmY3YzUtZmU3Zi00NzYwLTlhZTEtZjJlNjY2ZTdlZmQ5IgogICAgICBzdEV2dDpzb2Z0d2FyZUFnZW50PSJHaW1wIDIuMTAgKExpbnV4KSIKICAgICAgc3RFdnQ6d2hlbj0iLTA1OjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICAgPHhtcE1NOkRlcml2ZWRGcm9tCiAgICBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjU1QTc0Rjc4MzgyRjExRURCQTJCQzNGOEI1NDM2QUVFIgogICAgc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo1NUE3NEY3NzM4MkYxMUVEQkEyQkMzRjhCNTQzNkFFRSIvPgogICA8cGx1czpJbWFnZVN1cHBsaWVyPgogICAgPHJkZjpTZXEvPgogICA8L3BsdXM6SW1hZ2VTdXBwbGllcj4KICAgPHBsdXM6SW1hZ2VDcmVhdG9yPgogICAgPHJkZjpTZXEvPgogICA8L3BsdXM6SW1hZ2VDcmVhdG9yPgogICA8cGx1czpDb3B5cmlnaHRPd25lcj4KICAgIDxyZGY6U2VxLz4KICAgPC9wbHVzOkNvcHlyaWdodE93bmVyPgogICA8cGx1czpMaWNlbnNvcj4KICAgIDxyZGY6U2VxLz4KICAgPC9wbHVzOkxpY2Vuc29yPgogIDwvcmRmOkRlc2NyaXB0aW9uPgogPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgIAo8P3hwYWNrZXQgZW5kPSJ3Ij8+hUmggQAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB+cDDhMmCcSIqtAAABhWSURBVHja7V15nFTVlf7Ova+WXqu6aUQjuACKGBWZEdxGFFcybiEmM8FR9kB0jLL0ZieC7F1FA0bEsaEXcGNRMEFQQIhb4hIVNUrUyKZBEaGX6r2q3r1n/uiiqX61dkM6i31+v/dHvXfuudv3zj3n3HNfETpBp8+cCX99rWjqecogKegGZroMhAEEnG3lZaImYv6Aid4H4TVJYiu09lXl56GbuulEE3WEOdvjQZCop8G4i5jHMdEZnagzAGArEZYz86aagoLuWeimrgW0y+uFkZKSpRqbiojoHgDOE1E5AzsJ+JW9IP/FQ91z0U1dAej0OfNhM8SPWcqHifmUv0EbmInWB+z2yU1T7qvunpJu+psB2r3Aa4fAIwT8rAva8jVr/Lcm+n1dYefs60njJwCAC4AAYAKoX15RHpN/5C23ipycHBcBYObgisqKhjBZKUdXokAg0LLyySeaY9QpAWSGfgaXV5SHy7ABSA/99C+vKG+ylr/8skvR/8x+GQ6Hw+hgd5uWV5T7AWD86DHphmHYAEAzN5VVVvjD2pAGwB6pRRgrKitqxo8eg4rHV1n75ACQGvrZsryivDlRYyaMGZshpTQAQGndUL6yMhgmLwOAEV+rsbmisqI+rEw6AFs03paWlqDT6WyINrciVgXGg7OzSOD5LgIzAHyPBHaQwA+zFy/u3NtJBCL6hIiqiahaa31+LN6ePXshp0ePySLES0TrLLLmHpVjt9vnxKnzHDom41nLs5vDns2NVr7/mf1gt9vfCONL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';
            const imgWidth = 100;
            const customPDFFunct = ( doc ) => {
                doc.styles.title = {
                    color: '#11898a',
                    fontSize: '14',
                    display: 'none'
                };
                doc.styles.tableHeader= {
                    bold: true,
                    fontSize: 11,
                    color: 'white',
                    fillColor: '#11898a',
                    alignment: 'left',
                };
                doc.defaultStyle.fontSize = 10;
                doc.content.splice( 1, 0, {
                    margin: [ 0, 0, 0, -15 ],
                    alignment: 'right',
                    image: imageData,
                    width: imgWidth,
                });
            }

            const employees_data = Object.entries(base_data?.employee_data).map(([k,v]) => v);

            $('#dataTableResourceDetails').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'copy',
                    messageTop: 'Resource details: '+getStringProjectName()+getStringRange(),
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5,6 ]
                    }
                }, {
                    extend: 'pdf',
                    orientation:'portrait',
                    title: 'Cecropia / Multiplied CRM',
                    pageSize:'A4',
                    pageMargins: [40, 60, 40, 40],
                    message: 'Resource details: '+getStringProjectName()+getStringRange(),
                    customize: function (doc) { customPDFFunct(doc); } ,
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5,6 ]
                    }
                }, {
                    extend: 'csv',
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5,6 ],
                        orthogonal: 'export'
                    }
                },{
                    extend: 'excel',
                    messageTop: 'Resource details: '+getStringProjectName()+getStringRange(),
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5,6 ]
                    }
                },{
                    text: 'Activity Sync',
                    class: 'syncbutton',
                    action: function ( e, dt, node, config ) {
                        $(e.currentTarget).attr('disabled', true);
                        $(e.currentTarget).css("background-color", "#817C8D");
                        ConfirmDialog("Confirm Request",
                            "<p>The synchronization process  will be carried  out in the background.<br>"+
                            "This is an activity that can only be  executed once  while it is active.<br>"+
                            "according to the selected date range, it can take up to 5min per employee.<br>"+
                            "You will receive a notification when it is finished.</p>",
                            synchronizationAction,e)
                    }
                },'pageLength'],
                data: employees_data,
                processing: true,
                columnDefs: [
                    { width: "20%", targets: 0 }, { width: "20%", targets: 0 }
                ],
                columns: [
                    { data: 'name',
                        render: function(data, type, row) {
                            return viewDetails(row)+'&nbsp;'+renderLink('CC_Employee_Information',row.id,data);
                        }
                    },
                    { data: 'related_worktype',
                        render: function(data, type, row) {
                            let row_result = [];
                            row?.related_worktypes?.forEach(element => {
                                const worktype = (related_worktypes[element] !== undefined)?related_worktypes[element]:undefined;
                                if(worktype!== undefined){
                                    row_result.push(renderLink('CT_Modules_Worktype',element,worktype.name));
                                }
                            })
                            return (row_result.length>0)?row_result.join(" | "):"";
                        } },
                    { data: 'billable_time' ,render: function(data, type, row) { return (row?.billable_time)?row?.billable_time:0 } },
                    { data: 'invoiced_time' ,render: function(data, type, row) { return (row?.invoiced_time)?row?.invoiced_time:0 } },
                    { data: 'non_billable_time',render: function(data, type, row) { return (row?.non_billable_time)?row?.non_billable_time:0 } },
                    { data: 'pending_time' ,render: function(data, type, row) { return (row?.pending_time)?row?.pending_time:0 } },
                    { data: 'total',render: function(data, type, row) {
                        const total = (row?.total)?row?.total:0;
                        return  total;
                    } },
                ]
            });

            $('#dataTableActivityDetails').DataTable({
                ajax: {
                    "url": 'index.php?entryPoint=CTActivityEntryPoint',
                    "type": "POST",
                    "data": function(d){
                        d.action = "getEmployeeActivities";
                        d.activity_ids = employee_data?.activity_ids
                    }
                },
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'copy',
                    messageTop: function () {
                        return getEmployeeTitle() + ' on: ' + getStringProjectName() + getStringRange();
                    },
                    exportOptions: {
                        columns: [ 1,2,4,5,6,7,8 ],
                        orthogonal: 'export'
                    }
                }, {
                    extend: 'pdf',
                    orientation:'landscape',
                    title: 'Cecropia / Multiplied CRM',
                    pageSize:'A4',
                    pageMargins: [40, 60, 40, 40],
                    message: function () {
                        return getEmployeeTitle() + ' on: ' + getStringProjectName() + getStringRange();
                    },
                    customize: function (doc) { customPDFFunct(doc); } ,
                    exportOptions: {
                        columns: [ 1,2,4,5,6,7,8 ],
                        orthogonal: 'export'
                    }
                }, {
                    extend: 'csv',
                    exportOptions: {
                        columns: [1, 2, 4, 5, 6, 7, 8],
                        orthogonal: 'export'
                    }
                },{
                    extend: 'excel',
                    messageTop: function () {
                        return getEmployeeTitle() + ' on: ' + getStringProjectName() + getStringRange();
                    },
                    exportOptions: {
                        columns: [ 1,2,4,5,6,7,8 ],
                        orthogonal: 'export'
                    }
                }, 'pageLength'],
                processing: true,
                columnDefs: [
                    { targets: 0, visible: false, searchable: false },
                    { width: "10%", targets: 0 },
                    { width: "40%", targets: 0 },
                    { targets: 0, visible: false },
                ],
                columns: [
                    { data: 'id',  },
                    { data: 'activity_date' },
                    { data: 'description',width: "40%" },
                    { data: 'score', visible: false,render: function(data, type, row) { return (row?.score)?row?.score:0 } },
                    { data: 'activity_time',render: function(data, type, row) { return Math.round(((row?.activity_time)?row?.activity_time:0) * 100) / 100 } },
                    { data: 'is_billable',render: function(data, type, row) { return checkBoxRender(data,type,row?.id); } },
                    { data: 'module_worktype' ,render: function(data, type, row) { return workTypeRender(data,row) } },
                    { data: 'project_module' ,render: function(data, type, row) { return moduleRender(data,row) } },
                    { data: 'invoiced',render: function(data, type, row) { return checkBoxRender((row?.invoiced)?0:1,type,row?.id) } }
                ]
            });

            {/literal}{if $userHasRateAccess == 'true'}{literal}
            $('#dataTableRateDetails').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'copy',
                    messageTop: 'Worktype details: '+getStringProjectName()+getStringRange(),
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5,6,7 ]
                    }
                }, {
                    extend: 'pdf',
                    orientation:'portrait',
                    title: 'Cecropia / Multiplied CRM',
                    pageSize:'A4',
                    pageMargins: [40, 60, 40, 40],
                    message: 'Worktype details: '+getStringProjectName()+getStringRange(),
                    customize: function (doc) { customPDFFunct(doc); } ,
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5,6,7 ]
                    }
                }, {
                    extend: 'csv',
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5,6,7 ],
                        orthogonal: 'export'
                    }
                },{
                    extend: 'excel',
                    messageTop: 'Worktype details: '+getStringProjectName()+getStringRange(),
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5,6,7 ]
                    }
                },'pageLength'],
                data: Object.values(base_data.related_worktypes),
                processing: true,
                columnDefs: [
                    { width: "20%", targets: 0 }, { width: "20%", targets: 0 }
                ],
                columns: [
                    { data: 'name',
                        render: function(data, type, row) {
                            return renderLink('CT_Modules_Worktype',row.id,data);
                        }
                    },
                    { data: 'billable',
                        render: function(data, type, row) { return (row?.billable)?parseFloat(row?.billable).toFixed(2):0 }
                    },
                    { data: 'non_billable',render: function(data, type, row) {
                        return (row?.non_billable)?parseFloat(row?.non_billable).toFixed(2):0; } },
                    { data: 'invoiced' ,render: function(data, type, row) {
                        return (row?.invoiced)?parseFloat(row?.invoiced).toFixed(2):0; } },
                    { data: 'pending' ,render: function(data, type, row) {
                        return (row?.pending)?parseFloat(row?.pending).toFixed(2):0; } },
                    { data: 'total',render: function(data, type, row) {
                        return (row?.total)?parseFloat(row?.total).toFixed(2):0; } },
                    { data: 'cost' ,render: function(data, type, row) {
                        return (row?.cost)?parseFloat(row?.cost).toFixed(2)+'$ US':0; } },
                    { data: 'us_total' ,render: function(data, type, row) {
                        return (row?.cost && row?.billable && row?.invoiced)?
                            parseFloat(row.cost * (row.billable-row?.invoiced)).toFixed(2)+'$ US':0; }
                    },
                ]
            });
            {/literal}{/if}{literal}
        });
    </script>
{/literal}