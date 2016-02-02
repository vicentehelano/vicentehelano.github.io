#!/usr/bin/python

import sys, calendar
from datetime import datetime, time, date

# entrada: dias das aulas, primeiro dia, ultimo dia

first = datetime.strptime(sys.argv[1], "%d/%m/%Y")
last = datetime.strptime(sys.argv[2], "%d/%m/%Y")
days = [int(i) for i in sys.argv[3].split(',')]

print first
print last
print days

entrytext = '<tr>\n  <td class="lecture-number">%d</td>\n  <td class="lecture-date">%d/%d</td>\n  <td class="lecture-title">empty subject</td>\n  <td class="lecture-references">empty references</td>\n</tr>'

lecture_number = 0
for m in range( first.month, last.month+1 ):
  cal = calendar.monthcalendar(first.year,m)
  print "<!-- " + calendar.month_name[m] + " -->"
  for w in range(len(cal)):
    for j in days:
      dd = cal[w][j-2]
      if (m == last.month and dd > last.day):
        break
      if (dd != 0):
        lecture_number += 1
        print entrytext % (lecture_number, dd, m)
