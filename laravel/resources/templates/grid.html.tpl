<%= name %>
<div class="days">
  <% for(var day=0; day<days.length; day++) {%>
     <div class="day">
       <div class="heading">
         <h3><%= days[day].name %></h3> — <i><%= days[day].date %></i>
       </div>
       <div class="shift-wrap">
         <div class="timegrid">
           <div class="row hidden-xs hidden-sm">
             <div class="col-sm-2"></div>
             <div class="times col-sm-10">
               <div class="group col-md-12">
                 <div class="time col-md-1">
                   12 am
                 </div>

                 <div class="time col-md-1">
                   1 am
                 </div>

                 <div class="time col-md-1">
                   2 am
                 </div>

                 <div class="time col-md-1">
                   3 am
                 </div>

                 <div class="time col-md-1">
                   4 am
                 </div>

                 <div class="time col-md-1">
                   5 am
                 </div>

                 <div class="time col-md-1">
                   6 am
                 </div>

                 <div class="time col-md-1">
                   7 am
                 </div>

                 <div class="time col-md-1">
                   8 am
                 </div>

                 <div class="time col-md-1">
                   9 am
                 </div>

                 <div class="time col-md-1">
                   10 am
                 </div>

                 <div class="time col-md-1">
                   11 am
                 </div>
               </div> <!-- .group -->

               <div class="group col-md-12">
                 <div class="time col-md-1">
                   12 pm
                 </div>

                 <div class="time col-md-1">
                   1 pm
                 </div>

                 <div class="time col-md-1">
                   2 pm
                 </div>

                 <div class="time col-md-1">
                   3 pm
                 </div>

                 <div class="time col-md-1">
                   4 pm
                 </div>

                 <div class="time col-md-1">
                   5 pm
                 </div>

                 <div class="time col-md-1">
                   6 pm
                 </div>

                 <div class="time col-md-1">
                   7 pm
                 </div>

                 <div class="time col-md-1">
                   8 pm
                 </div>

                 <div class="time col-md-1">
                   9 pm
                 </div>

                 <div class="time col-md-1">
                   10 pm
                 </div>

                 <div class="time col-md-1">
                   11 pm
                 </div>
               </div> <!-- .group -->
             </div> <!-- / .heading -->
           </div> <!-- / .row -->

           <div class="row hidden-xs hidden-sm">
             <div class="col-sm-2"></div>
             <div class="background col-sm-10" style="height: 242px;">
               <div class="group col-md-12">
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
               </div> <!-- .group -->

               <div class="group col-md-12">
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
                 <div class="time col-md-1"></div>
               </div> <!-- .group -->
             </div> <!-- / .background -->
           </div> <!-- / .row -->
         </div> <!-- / .timegrid -->
         <div class="department-wrap">
           <% for(var department=0; department < days[day].departments.length; department++) {%>
              <div class="department">
                <div class="title">
                  <a href="/department/<%= days[day].departments[department].id %>/edit"><%= days[day].departments[department].name %></a><br>
                </div>
                <ul class="shifts">
                  <% for(var shift=0; shift < days[day].departments[department].shifts.length; shift++) {%>
                     <li class="shift row" data-rows="6">
                       <div class="title col-sm-2" style="height: 12em;">
                         <a href="/schedule/<%= days[day].departments[department].shifts[shift].id %>/edit"><%= days[day].departments[department].shifts[shift].name %></a>
                       </div>

                       <div class="slots col-sm-10">
                         <% for(var slot=0; slot < days[day].departments[department].shifts[shift].slots.length; slot++) {%>

                            <span class="slot-wrap" data-start="<%= days[day].departments[department].shifts[shift].slots[slot].start_date %>" data-duration="<%= days[day].departments[department].shifts[shift].slots[slot].duration %>" data-row="<%= days[day].departments[department].shifts[shift].slots[slot].row %>">
                           <a class="slot empty" data-id="65" title="<%= days[day].departments[department].shifts[shift].slots[slot].title %>"><%= days[day].departments[department].shifts[shift].slots[slot].title %></a>
                         </span>
                         <% } %>
                       </div>
                     </li>
                     <% } %>
                </ul>
              </div>
              <% } %>
         </div> <!-- / .department-wrap -->
       </div> <!-- / .shift-wrap -->
     </div>
     <% } %>
</div>
