<?php
$this->Html->scriptBlock("
    $('.tempo').tooltip({
        selector: 'th[rel=tooltip]'
    });
", array('inline' => false));
?>
<div class="">
    <div class="row-fluid">
        <div>
            <table class="well table table-condensed table-striped tempo">
                <thead>
                    <tr>
                        <th>Task</th>

                        <th width="5%" class="tempoHeader " rel="tooltip" data-original-title="2012-10-08">Mon</th>

                        <th width="5%" class="tempoHeader " rel="tooltip" data-original-title="2012-10-09">Tue</th>

                        <th width="5%" class="tempoHeader " rel="tooltip" data-original-title="2012-10-10">Wed</th>

                        <th width="5%" class="tempoHeader today" rel="tooltip" data-original-title="2012-10-11">Thu</th>

                        <th width="5%" class="tempoHeader " rel="tooltip" data-original-title="2012-10-12">Fri</th>

                        <th width="5%" class="tempoHeader " rel="tooltip" data-original-title="2012-10-13">Sat</th>

                        <th width="5%" class="tempoHeader " rel="tooltip" data-original-title="2012-10-14">Sun</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><a href="#">Create a new time tracking interface</a></td>

                        <td data-date="2012-10-08" data-taskid="14" class="tempoBody " data-toggle="tempo_Mon_14">1</td>

                        <td data-date="2012-10-09" data-taskid="14" class="tempoBody " data-toggle="tempo_Tue_14"></td>

                        <td data-date="2012-10-10" data-taskid="14" class="tempoBody " data-toggle="tempo_Wed_14"></td>

                        <td data-date="2012-10-11" data-taskid="14" class="tempoBody today" data-toggle="tempo_Thu_14"></td>

                        <td data-date="2012-10-12" data-taskid="14" class="tempoBody " data-toggle="tempo_Fri_14"></td>

                        <td data-date="2012-10-13" data-taskid="14" class="tempoBody " data-toggle="tempo_Sat_14"></td>

                        <td data-date="2012-10-14" data-taskid="14" class="tempoBody " data-toggle="tempo_Sun_14"></td>
                    </tr>

                    <tr>
                        <td>No associated task</td>

                        <td data-date="2012-10-08" data-taskid="0" class="tempoBody " data-toggle="tempo_Mon_0"></td>

                        <td data-date="2012-10-09" data-taskid="0" class="tempoBody " data-toggle="tempo_Tue_0"></td>

                        <td data-date="2012-10-10" data-taskid="0" class="tempoBody " data-toggle="tempo_Wed_0"></td>

                        <td data-date="2012-10-11" data-taskid="0" class="tempoBody today" data-toggle="tempo_Thu_0"></td>

                        <td data-date="2012-10-12" data-taskid="0" class="tempoBody " data-toggle="tempo_Fri_0"></td>

                        <td data-date="2012-10-13" data-taskid="0" class="tempoBody " data-toggle="tempo_Sat_0"></td>

                        <td data-date="2012-10-14" data-taskid="0" class="tempoBody " data-toggle="tempo_Sun_0"></td>
                    </tr>

                    <tr>
                        <td><strong>Total</strong></td>

                        <td class="tempoFooter ">1</td>

                        <td class="tempoFooter "></td>

                        <td class="tempoFooter "></td>

                        <td class="tempoFooter today"></td>

                        <td class="tempoFooter "></td>

                        <td class="tempoFooter "></td>

                        <td class="tempoFooter "></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>
