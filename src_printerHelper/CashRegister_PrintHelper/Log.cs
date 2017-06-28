using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace CashRegister_PrintHelper
{
    public class Log
    {
        public Log(string _msg)
        {
            msg = _msg;
            time = DateTime.Now;
        }

        public DateTime time { get; set; }
        public string msg { get; set; }
    }
}
