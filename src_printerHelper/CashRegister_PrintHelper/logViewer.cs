using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace CashRegister_PrintHelper
{
    public partial class logViewer : Form
    {
        public Settings prefs;

        public logViewer(Settings prefs)
        {
            InitializeComponent();
            this.prefs = prefs;
        }

        private void logViewer_Load(object sender, EventArgs e)
        {
            foreach(Log l in prefs.printLog)
            {
                ListViewItem item = new ListViewItem();
                item.Text = l.time.ToShortTimeString() + " - " + l.time.ToLongDateString();
                item.SubItems.Add(l.msg);

                listView1.Items.Add(item);
            }
        }
    }
}
